<?php namespace Test\Appointment\Models\Reception;

use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeService;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\Resource;
use App\Appointment\Models\ResourceService;
use App\Appointment\Models\Reception\BackendReceptionist;
use App\Core\Models\User;
use App\Consumers\Models\Consumer;
use Carbon\Carbon;
use \UnitTester;
use DB;
use Test\Traits\Models;

/**
 * @group as
 */
class BackendReceptionCest
{
    use Models;

    protected $receptionist = null;

    private function basicSetup()
    {
        //Do we really need a common receptionist for all test?
    }

    public function testBookingServicePrice(UnitTester $I)
    {
        $this->initData();
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = Booking::uuid();

        $date      = $this->getDate();
        $startTime = '10:00';

        $I->amLoggedAs($user);

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID($uuid)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee->id)
            ->setServiceTimeId('default');

        $bookingService = $receptionist->upsertBookingService();
        $I->assertEmpty($receptionist->getBookingServices());
        $I->assertEquals($service->price, $receptionist->computeTotalPrice());
    }

    public function testValidateData(UnitTester $I)
    {
        $this->initData();
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = Booking::uuid();

        $date      = $this->getDate();
        $startTime = '10:00';

        $I->amLoggedAs($user);
        $I->assertEquals($service->length, 60);
        $I->assertNotEmpty($service);
        $I->assertNotEmpty($employee);

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID($uuid)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee->id)
            ->setServiceTimeId('default')
            ->setModifyTime(-60)
            ->setIsRequestedEmployee(false);

        $exception = array();

        try {
           $receptionist->validateBookingTotal();
        } catch(\Exception $ex) {
            $exception[] = $ex->getMessage();
        }

        $I->assertNotEmpty($exception[0]);
        $I->assertEquals($exception[0], trans('as.bookings.error.empty_total_time'));

        $receptionist->setModifyTime(0)->setStartTime('23:30');

        try {
           $receptionist->validateBookingTime();
        } catch(\Exception $ex) {
            $exception[] = $ex->getMessage();
        }
        $I->assertNotEmpty($exception[1]);
        $I->assertEquals($exception[1], trans('as.bookings.error.exceed_current_day'));
    }

    public function testValidateBooking(UnitTester $I)
    {

        $this->initData();
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = Booking::uuid();

        $date      = $this->getDate();
        $startTime = '13:00';

        $I->amLoggedAs($user);
        $I->assertEquals($service->length, 60);

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID($uuid)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee->id)
            ->setServiceTimeId('default');

        $exception = array();

        try {
           $receptionist->validateWithEmployeeFreetime();
        } catch(\Exception $ex) {
            $exception[] = $ex->getMessage();
        }

        $I->assertNotEmpty($exception[0]);
        $I->assertEquals($exception[0], trans('as.bookings.error.overllapped_with_freetime'));
    }

    public function testValidateWithRoom(UnitTester $I)
    {
        $this->initData(true, false, true);
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = Booking::uuid();

        $date      = $this->getDate();
        $startTime = '09:00';

        $I->amLoggedAs($user);
        $I->assertEquals($service->length, 60);

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID($uuid)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee->id)
            ->setServiceTimeId('default');

        $receptionist->validateWithRooms();

        $I->assertEquals($receptionist->getRoomId(), 1);

        $receptionist->upsertBookingService();

        $consumer = Consumer::handleConsumer([
            'first_name' => 'Consumer First',
            'last_name' => 'Last ' . $this->service->id,
            'email' => 'consumer_' . $this->service->id . '@varaa.com',
            'phone' => '1234567890',
            'hash' => '',
        ], $user);

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(null)
            ->setUUID($uuid)
            ->setUser($user)
            ->setStatus('confirmed')
            ->setNotes('')
            ->setIsRequestedEmployee(true)
            ->setConsumer($consumer)
            ->setClientIP('192.168.1.1')
            ->setSource('backend');

        $receptionist->setBookingService();

        $booking = $receptionist->upsertBooking();

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID(Booking::uuid())
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId(64)
            ->setServiceTimeId('default');

        $exception = array();
        try {
           $receptionist->validateWithRooms();
        } catch(\Exception $ex) {
            $exception[] = $ex->getMessage();
        }

        $I->assertNotEmpty($exception[0]);
        $I->assertEquals($exception[0], trans('as.bookings.error.not_enough_rooms'));
    }

    public function testResponseData(UnitTester $I)
    {
        $this->initData();
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = Booking::uuid();

        $date      = $this->getDate();
        $startTime = '14:00';

        $I->amLoggedAs($user);
        $I->assertEquals($service->length, 60);

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID($uuid)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee->id)
            ->setServiceTimeId('default');

        $bookingService = $receptionist->upsertBookingService();
        $response = $receptionist->getResponseData();

        $plustime = $employee->getPlustime($service->id);

        $cooked = [
            'datetime'           => $date->hour(14)->toDateString(),
            'booking_service_id' => $bookingService->id,
            'booking_id'         => 0,
            'start_time'         => $startTime,
            'price'              => $service->price,
            'total_price'        => $service->price,
            'total_length'       => '60 (1 hr)',
            'category_id'        => $service->category->id,
            'service_id'         => $service->id,
            'service_name'       => $service->name,
            'service_time'       => 'default',
            'service_length'     => $service->length,
            'plustime'           => intval($plustime),
            'employee_name'      => $employee->name,
            'uuid'               => $uuid
        ];

        $I->assertEquals($response, $cooked);
    }

    public function testUpsertBooking(UnitTester $I)
    {
        $this->initData();
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = Booking::uuid();

        $date      = $this->getDate();
        $startTime = '14:00';

        $booking = $this->_makeBooking($I, $user, $employee, $uuid, $service, $date, $startTime);

        $I->assertNotEmpty($booking);
        $I->assertEquals($booking->total, 60);
        $I->assertEquals($booking->startTime, $date->hour(14)->minute(0)->second(0));
        $I->assertEquals($booking->endTime, $date->hour(15)->minute(0)->second(0));
    }

    public function testOverllapedBooking(UnitTester $I)
    {
        $this->initData();
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = Booking::uuid();

        $date      = $this->getDate();
        $startTime = '14:00';

        $this->_makeBooking($I, $user, $employee, $uuid, $service, $date, $startTime);

        //new booking
        $uuid = Booking::uuid();
        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID($uuid)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee->id)
            ->setServiceTimeId('default');

        $exception = array();

        try{
            $bookingService = $receptionist->upsertBookingService();
        } catch(\Exception $ex) {
            $exception[] = $ex->getMessage();
        }
        $I->assertNotEmpty($exception[0]);
        $I->assertEquals($exception[0], trans('as.bookings.error.add_overlapped_booking'));
    }

    private function _makeBooking($I, $user, $employee, $uuid, $service, $date, $startTime, $extraServices = [])
    {
        //$I->amLoggedAs($user);
        $I->assertEquals($service->length, 60);

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID($uuid)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee->id)
            ->setServiceTimeId('default')
            ->setExtraServiceIds($extraServices);

        $bookingService = $receptionist->upsertBookingService();

        $consumer = Consumer::handleConsumer([
            'first_name' => 'Consumer First',
            'last_name' => 'Last ' . $this->service->id,
            'email' => 'consumer_' . $this->service->id . '@varaa.com',
            'phone' => '1234567890',
            'hash' => '',
        ], $user);

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(null)
            ->setUUID($uuid)
            ->setUser($user)
            ->setStatus('confirmed')
            ->setNotes('')
            ->setIsRequestedEmployee(true)
            ->setConsumer($consumer)
            ->setClientIP('192.168.1.1')
            ->setSource('backend');

        $receptionist->setBookingService();

        $bookingService = $receptionist->getBookingService();

        $booking = $receptionist->upsertBooking();
        return $booking;
    }

    public function testDeleteExtraServices(UnitTester $I)
    {
        $this->initData();
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = Booking::uuid();

        $date      = $this->getDate();
        $startTime = '14:00';

        $extraServicesIds = array(10, 11);
        $booking = $this->_makeBooking($I, $user, $employee, $uuid, $service, $date, $startTime, $extraServicesIds);

        $I->assertNotEmpty($booking);
        $I->assertEquals($booking->total, 60+30);
        $I->assertEquals($booking->startTime, $date->hour(14)->minute(0)->second(0));
        $I->assertEquals($booking->endTime, $date->hour(15)->minute(30)->second(0));

        $I->assertNotEmpty($booking->id);
        //delete extra services
        $bookingExtraServices = BookingExtraService::whereIn('extra_service_id', $extraServicesIds)
                ->where('tmp_uuid', $uuid)
                ->get();
        $I->assertNotEmpty($bookingExtraServices);
        foreach ($bookingExtraServices  as $bookingExtraService) {
            $bookingExtraService->delete();
        }

        $bookingExtraServices = BookingExtraService::whereIn('extra_service_id', $extraServicesIds)
                ->where('tmp_uuid', $uuid)
                ->get();

        $I->assertEquals(0, $bookingExtraServices->count());
        $I->assertEmpty($bookingExtraServices);

        $receptionist = new BackendReceptionist();
        $receptionist->setUUID($uuid)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setBookingId($booking->id)
            ->updateBookingServicesTime();

        $consumer = Consumer::handleConsumer([
            'first_name' => 'Consumer First',
            'last_name' => 'Last ' . $this->service->id,
            'email' => 'consumer_' . $this->service->id . '@varaa.com',
            'phone' => '1234567890',
            'hash' => '',
        ], $user);

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId($booking->id)
            ->setUUID($uuid)
            ->setUser($user)
            ->setStatus('confirmed')
            ->setNotes('')
            ->setIsRequestedEmployee(true)
            ->setConsumer($consumer)
            ->setClientIP('192.168.1.1')
            ->setSource('backend');

        $booking = $receptionist->upsertBooking();

        $I->assertNotEmpty($booking);
        $I->assertEquals($booking->total, 60);
        $I->assertEquals($booking->startTime, $date->hour(14)->minute(0)->second(0));
        $I->assertEquals($booking->endTime, $date->hour(15)->minute(0)->second(0));
    }

    public function testMultipleBookingServices(UnitTester $I)
    {
        $this->initData();
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = Booking::uuid();

        $date      = $this->getDate();
        $startTime = '14:00';

        $I->amLoggedAs($user);
        $I->assertEquals($service->length, 60);

        $bookingService1 = $this->makeBookingService($uuid, $user, $date, $startTime, $service, $employee);

        $I->assertEquals($bookingService1->startTime, $date->hour(14)->minute(0)->second(0));
        $I->assertEquals($bookingService1->endTime, $date->hour(15)->minute(0)->second(0));

        $service1 = new Service([
            'name'      => 'Hiusjuuritutkimus',
            'length'    => 60,
            'during'    => 45,
            'after'     => 15,
            'price'     => 35,
            'is_active' => 1,
        ]);
        $category = ServiceCategory::findOrFail(106);
        $service1->user()->associate($user);
        $service1->category()->associate($category);
        $service1->saveOrFail();

        $service1->employees()->attach($employee);

        $bookingService2 = $this->makeBookingService($uuid, $user, $date, '15:00', $service1, $employee);
        $I->assertEquals($this->services[1]->length, 60);
        $I->assertEquals($bookingService2->startTime, $date->hour(15)->minute(0)->second(0));
        $I->assertEquals($bookingService2->endTime, $date->hour(16)->minute(0)->second(0));

        $consumer = Consumer::handleConsumer([
            'first_name' => 'Consumer First',
            'last_name' => 'Last ' . $this->service->id,
            'email' => 'consumer_' . $this->service->id . '@varaa.com',
            'phone' => '1234567890',
            'hash' => '',
        ], $user);

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(null)
            ->setUUID($uuid)
            ->setUser($user)
            ->setStatus('confirmed')
            ->setNotes('')
            ->setIsRequestedEmployee(true)
            ->setConsumer($consumer)
            ->setClientIP('192.168.1.1')
            ->setSource('backend');
        // $bs = BookingService::where('tmp_uuid', $uuid)->orderBy('start_at')->get();
        // $I->assertEmpty($bs);

        $booking = $receptionist->upsertBooking();
        $I->assertNotEmpty($booking);
        $I->assertEquals($booking->total, 120);
        $I->assertEquals($booking->startTime, $date->hour(14)->minute(0)->second(0));
        $I->assertEquals($booking->endTime, $date->hour(16)->minute(0)->second(0));
    }

    public function editBookingHasModifyTime(UnitTester $I)
    {

    }

    public function testEditBookingServiceBeforeSaveBooking(UnitTester $I)
    {
        $this->initData();
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = Booking::uuid();

        $date      = $this->getDate();
        $startTime = '14:00';

        $I->amLoggedAs($user);
        $I->assertEquals($service->length, 60);

        $bookingService1 = $this->makeBookingService($uuid, $user, $date, $startTime, $service, $employee);

        $I->assertEquals($bookingService1->startTime, $date->hour(14)->minute(0)->second(0));
        $I->assertEquals($bookingService1->endTime, $date->hour(15)->minute(0)->second(0));

        $I->assertNotEmpty($bookingService1->id);

        $service1 = new Service([
            'name'      => 'Hiusjuuritutkimus',
            'length'    => 60,
            'during'    => 45,
            'after'     => 15,
            'price'     => 35,
            'is_active' => 1,
        ]);
        $category = ServiceCategory::findOrFail(106);
        $service1->user()->associate($user);
        $service1->category()->associate($category);
        $service1->saveOrFail();

        $service1->employees()->attach($employee);

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID($uuid)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setBookingServiceId($bookingService1->id)
            ->setServiceId($service1->id)
            ->setEmployeeId($employee->id)
            ->setServiceTimeId('default')
            ->setModifyTime(0)
            ->setIsRequestedEmployee(false);

        try{
            $bookingService2 = $receptionist->upsertBookingService();
        }catch(\Exception $ex) {
            $I->assertEmpty($ex);
        }

        $bookingServices = BookingService::where('tmp_uuid', $uuid)
            ->whereNull('deleted_at')->orderBy('start_at')->get();
        $I->assertEquals($bookingServices->count(), 1);

        $I->assertEquals($bookingService2->startTime, $date->hour(14)->minute(0)->second(0));
        $I->assertEquals($bookingService2->endTime, $date->hour(15)->minute(0)->second(0));
        $I->assertEquals($bookingService2->service->id,  $service1->id);
    }

    protected function makeBookingService($uuid, $user, $date, $startTime, $service, $employee)
    {
        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID($uuid)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee->id)
            ->setServiceTimeId('default');

        return $receptionist->upsertBookingService();
    }

    public function testBookingResourcesQuantity(UnitTester $I)
    {
        $this->initData(true, true);
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;
        $employee1 = Employee::find(64);
        $employee2 = Employee::find(65);
        $employee3 = Employee::find(66);

        $service   = $this->service;
        $uuid      = Booking::uuid();

        $date      = $this->getDate();
        $startTime = '14:00';

        $booking1 = $this->_makeBooking($I, $user, $employee,  $uuid, $service, $date, $startTime);
        $uuid1     = Booking::uuid();
        $booking2 = $this->_makeBooking($I, $user, $employee1, $uuid1, $service, $date, $startTime);
        $uuid2     = Booking::uuid();
        $booking3 = $this->_makeBooking($I, $user, $employee2, $uuid2, $service, $date, $startTime);

        //new booking
        $uuid3 = Booking::uuid();
        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID($uuid3)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee3->id)
            ->setServiceTimeId('default');

        $exception = array();

        try{
            $bookingService = $receptionist->upsertBookingService();
        } catch(\Exception $ex) {
            $exception[] = $ex->getMessage();
        }
        $I->assertNotEmpty($exception[0]);
        $I->assertEquals($exception[0], trans('as.bookings.error.not_enough_resources'));
        //remove one booking for available resource
        $booking3->delete();

        $uuid4 = Booking::uuid();
        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId(0)
            ->setUUID($uuid4)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee3->id)
            ->setServiceTimeId('default');

        $exception = array();

        try{
            $bookingService = $receptionist->upsertBookingService();
        } catch(\Exception $ex) {
            $exception[] = $ex->getMessage();
        }
        $I->assertEmpty($exception);
    }

    public function testAddExtraServices(UnitTester $I)
    {
        $this->initData(true, true);
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;

        $service   = $this->service;
        $uuid      = Booking::uuid();
        $date      = $this->getDate();
        $startTime = '14:00';
        $booking = $this->_makeBooking($I, $user, $employee,  $uuid, $service, $date, $startTime);

        $I->assertNotEmpty($booking);
        $I->assertEquals($booking->total, 60);
        $I->assertEquals($booking->startTime, $date->hour(14)->minute(0)->second(0));
        $I->assertEquals($booking->endTime, $date->hour(15)->minute(0)->second(0));

        $extraServiceIds = array(10, 11);

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId($booking->id)
                ->setUUID($booking->uuid)
                ->setUser($user)
                ->setStatus($booking->getStatusText())
                ->setNotes($booking->notes)
                ->setBookingDate($booking->date)
                ->setModifyTime(0)
                ->setIsRequestedEmployee($booking->isRequestedEmployee)
                ->setConsumer($booking->consumer)
                ->setExtraServiceIds($extraServiceIds)
                ->setClientIP('127.0.0.1')
                ->setSource('backend');

        $booking = $receptionist->upsertBooking();

        $bookingServices = BookingExtraService::where('tmp_uuid', $booking->uuid)->whereNull('deleted_at')->count();
        $I->assertEquals($bookingServices, 2);


        $I->assertNotEmpty($booking);
        $I->assertEquals($booking->total, 60+15+15);
        $I->assertEquals($booking->startTime, $date->hour(14)->minute(0)->second(0));
        $I->assertEquals($booking->endTime, $date->hour(15)->minute(30)->second(0));
    }

    public function testAddExtraServicesCheckOverlapping(UnitTester $I)
    {
        $this->initData(true, true);
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;

        $service   = $this->service;
        $uuid      = Booking::uuid();
        $date      = $this->getDate();
        $startTime = '14:00';
        $booking = $this->_makeBooking($I, $user, $employee,  $uuid, $service, $date, $startTime);

        $I->assertNotEmpty($booking);
        $I->assertEquals($booking->total, 60);
        $I->assertEquals($booking->startTime, $date->hour(14)->minute(0)->second(0));
        $I->assertEquals($booking->endTime, $date->hour(15)->minute(0)->second(0));


        $startTime = '15:15';
        $uuid      = Booking::uuid();
        $booking1 = $this->_makeBooking($I, $user, $employee,  $uuid, $service, $date, $startTime);

        $I->assertNotEmpty($booking1);
        $I->assertEquals($booking1->total, 60);
        $I->assertEquals($booking1->startTime, $date->hour(15)->minute(15)->second(0));
        $I->assertEquals($booking1->endTime, $date->hour(16)->minute(15)->second(0));

        $extraServiceIds = array(10, 11);

        $receptionist = new BackendReceptionist();
        $receptionist->setBookingId($booking->id)
                ->setUUID($booking->uuid)
                ->setUser($user)
                ->setStatus($booking->getStatusText())
                ->setNotes($booking->notes)
                ->setBookingDate($booking->date)
                ->setModifyTime(0)
                ->setIsRequestedEmployee($booking->isRequestedEmployee)
                ->setConsumer($booking->consumer)
                ->setExtraServiceIds($extraServiceIds)
                ->setClientIP('127.0.0.1')
                ->setSource('backend');

        try{
            $booking = $receptionist->upsertBooking();
        } catch (\Exception $ex) {
            $exception[] = $ex->getMessage();
            $receptionist->rollBack();
        }
        $I->assertNotEmpty($exception[0]);

        $bookingServices = BookingExtraService::where('tmp_uuid', $booking->uuid)->whereNull('deleted_at')->count();
        $I->assertEquals($bookingServices, 0);
    }

    public function testDuplicatedBookings(UnitTester $I)
    {
        $this->initData(true, true);
        $this->initCustomTime();

        $user      = User::find(70);
        $employee  = $this->employee;

        $service   = $this->service;
        $uuid      = Booking::uuid();
        $date      = $this->getDate();
        $startTime = '09:45';
        $booking   = $this->_makeBooking($I, $user, $employee,  $uuid, $service, $date, $startTime);
        $I->assertEquals($booking->uuid, $uuid);

        try{
            $uuid      = Booking::uuid();
            $startTime = '09:30';
            $booking   = $this->_makeBooking($I, $user, $employee,  $uuid, $service, $date, $startTime);
            $I->assertEquals($booking->uuid, $uuid);
        } catch(\Exception $ex) {
            $I->assertEquals($ex->getMessage(), trans('as.bookings.error.add_overlapped_booking'));
        }
    }
}
