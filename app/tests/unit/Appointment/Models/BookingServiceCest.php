<?php namespace Test\Appointment\Models;

use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeFreetime;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\ServiceTime;
use App\Core\Models\User;
use \UnitTester;
use Util;

/**
 * @group as
 */
class BookingServiceCest
{
    use \Test\Traits\Booking;

    public function testGetCartStartAt(UnitTester $I)
    {
        $bookingService = new BookingService([
            'start_at' => '12:00:00',
        ]);

        // service #301 has no before time
        $bookingService->service()->associate(Service::find(301));
        $startAt = $bookingService->getCartStartAt();
        $I->assertEquals('12:00:00', $startAt->toTimeString(), 'no before time');

        // service #302 has 15 minute before time
        $bookingService->service()->associate(Service::find(302));
        $startAt = $bookingService->getCartStartAt();
        $I->assertEquals('12:15:00', $startAt->toTimeString(), '15 minute before time');

        // service time #1 has 30 minute before time
        $bookingService->service()->associate(Service::find(301));
        $bookingService->serviceTime()->associate(ServiceTime::find(1));
        $startAt = $bookingService->getCartStartAt();
        $I->assertEquals('12:30:00', $startAt->toTimeString(), '30 minute before time');
    }

    public function testGetCartEndAt(UnitTester $I)
    {
        $bookingService = new BookingService([
            'tmp_uuid' => Util::uuid(),
            'start_at' => '12:00:00',
        ]);
        $bookingService->employee()->associate(Employee::find(63));

        // service #301 has no before time
        $bookingService->service()->associate(Service::find(301));
        $endAt = $bookingService->getCartEndAt();
        $I->assertEquals('12:30:00', $endAt->toTimeString(), 'no before time');

        // service #302 has 15 minute before time
        $bookingService->service()->associate(Service::find(302));
        $endAt = $bookingService->getCartEndAt();
        $I->assertEquals('12:45:00', $endAt->toTimeString(), '15 minute before time');

        // service time #1 has 30 minute before time
        $bookingService->service()->associate(Service::find(301));
        $bookingService->serviceTime()->associate(ServiceTime::find(1));
        $endAt = $bookingService->getCartEndAt();
        $I->assertEquals('14:10:00', $endAt->toTimeString(), '30 minute before time');

        // service #301 with employee #64 with 15 minute plus time
        $bookingService = new BookingService([
            'tmp_uuid' => Util::uuid(),
            'start_at' => '12:00:00',
        ]);
        $bookingService->employee()->associate(Employee::find(64));
        $bookingService->service()->associate(Service::find(301));
        $endAt = $bookingService->getCartEndAt();
        $I->assertEquals('12:45:00', $endAt->toTimeString(), 'no before time, 15 minute plus time');
    }

    public function testGetExtraServices(UnitTester $I)
    {
        $uuid = Util::uuid();
        $extraService = ExtraService::find(1);

        for ($i = 0; $i < 3; $i++) {
            $bookingExtraService = new BookingExtraService([
                'tmp_uuid' => $uuid,
            ]);
            $bookingExtraService->extraService()->associate($extraService);
            $bookingExtraService->saveOrFail();

            $bookingService = new BookingService([
                'tmp_uuid' => $uuid,
            ]);

            $price = $bookingService->getExtraServicePrice();
            $I->assertEquals($extraService->price * ($i + 1), $price, ($i + 1) . ' extra service(s) - price');

            $time = $bookingService->getExtraServiceTime();
            $I->assertEquals($extraService->length * ($i + 1), $time, ($i + 1) . ' extra service(s) - time');
        }
    }

    public function testGetEmployeePlustime(UnitTester $I)
    {
        $bookingService = new BookingService([
            'tmp_uuid' => Util::uuid(),
            'start_at' => '12:00:00',
        ]);
        $bookingService->service()->associate(Service::find(301));
        $bookingService->employee()->associate(Employee::find(63));
        $plustime = $bookingService->getEmployeePlustime();
        $I->assertEquals(0, $plustime, 'no plus time');

        $bookingService->employee()->associate(Employee::find(64));
        $plustime = $bookingService->getEmployeePlustime();
        $I->assertEquals(0, $plustime, 'plus time but result cached');

        $bookingService = new BookingService([
            'tmp_uuid' => Util::uuid(),
            'start_at' => '12:00:00',
        ]);
        $bookingService->service()->associate(Service::find(301));
        $bookingService->employee()->associate(Employee::find(64));
        $plustime = $bookingService->getEmployeePlustime();
        $I->assertEquals(15, $plustime, '15 minute plus time');
    }

    public function testCalculateServiceLengthAndPrice(UnitTester $I)
    {
        // service #301
        $bookingService = new BookingService();
        $bookingService->service()->associate(Service::find(301));
        $bookingService->employee()->associate(Employee::find(63));
        $length = $bookingService->calculateServiceLength();
        $price = $bookingService->calculateServicePrice();
        $I->assertEquals(45, $length, 'service #301 - length');
        $I->assertEquals(35, $price, 'service #301 - price');

        // service time #1
        $bookingService = new BookingService();
        $bookingService->service()->associate(Service::find(301));
        $bookingService->serviceTime()->associate(ServiceTime::find(1));
        $bookingService->employee()->associate(Employee::find(63));
        $length = $bookingService->calculateServiceLength();
        $price = $bookingService->calculateServicePrice();
        $I->assertEquals(160, $length, 'service time #1 - length');
        $I->assertEquals(50, $price, 'service time #1 - price');

        // service #301, employee #64
        $bookingService = new BookingService();
        $bookingService->service()->associate(Service::find(301));
        $bookingService->employee()->associate(Employee::find(64));
        $length = $bookingService->calculateServiceLength();
        $price = $bookingService->calculateServicePrice();
        $I->assertEquals(60, $length, 'service #301 + employee #64 - length');
        $I->assertEquals(35, $price, 'service #301 + employee #64 - price');

        // service #301, modify time
        $bookingService = new BookingService(['modify_time' => 30]);
        $bookingService->service()->associate(Service::find(301));
        $bookingService->employee()->associate(Employee::find(63));
        $length = $bookingService->calculateServiceLength();
        $price = $bookingService->calculateServicePrice();
        $I->assertEquals(75, $length, 'service #301 + modify time - length');
        $I->assertEquals(35, $price, 'service #301 + modify time - price');

        // TODO: test calculcateTotalLength
        // TODO: test calculcateTotalPrice
        // TODO: test getCartDetailPrice
    }

    public function testSaveBookingServiceSuccess(UnitTester $I)
    {
        $uuid = \Util::uuid();
        $employee = Employee::find(63);
        $service = Service::find(301);
        $date = $this->_getNextDate();
        $startTime = '12:00';
        $input = [
            'booking_date' => $date->toDateString(),
            'start_time' => $startTime,
        ];

        $bookingService = BookingService::saveBookingService($uuid, $employee, $service, $input);
        $I->assertNotEmpty($bookingService, 'booking service');

        $I->assertEquals($uuid, $bookingService->tmp_uuid, 'tmp_uuid');
        $I->assertEquals($employee->user->id, $bookingService->user_id, 'user_id');
        $I->assertEmpty($bookingService->booking_id, 'booking_id');
        $I->assertEquals($service->id, $bookingService->service_id, 'service_id');
        $I->assertEmpty($bookingService->service_time_id, 'service_time_id');
        $I->assertEquals($employee->id, $bookingService->employee_id, 'employee_id');
        $I->assertEmpty($bookingService->mofify_time, 'mofify_time');
        $I->assertEquals($date->toDateString(), $bookingService->date, 'date');
        $I->assertEquals(substr($input['start_time'], 0, 5), substr($bookingService->start_at, 0, 5), 'start_at');
        $I->assertEquals($service->length, $bookingService->getEndAt()->diffInMinutes($bookingService->getStartAt()), 'length');
    }

    public function testSaveBookingServiceDateTimeFormatting(UnitTester $I)
    {
        $uuid = \Util::uuid();
        $employee = Employee::find(63);
        $service = Service::find(301);

        $e = null;
        try {
            BookingService::saveBookingService($uuid, $employee, $service, []);
        } catch (\Exception $_e) {
            $e = $_e;
        }
        $I->assertNotEmpty($e, 'exception no date time');

        $e = null;
        try {
            BookingService::saveBookingService($uuid, $employee, $service, [
                'date' => '01-01-2014',
                'time' => '12:00',
            ]);
        } catch (\Exception $_e) {
            $e = $_e;
        }
        $I->assertNotEmpty($e, 'exception wrong date format');

        $e = null;
        try {
            BookingService::saveBookingService($uuid, $employee, $service, [
                'date' => $this->_getNextDate()->toDateString(),
                'time' => '12h',
            ]);
        } catch (\Exception $_e) {
            $e = $_e;
        }
        $I->assertNotEmpty($e, 'exception wrong time format');
    }

    public function testSaveBookingServiceServiceTime(UnitTester $I)
    {
        $uuid = \Util::uuid();
        $employee = Employee::find(63);
        $service = Service::find(301);
        $date = $this->_getNextDate();
        $startTime = '12:00';
        $input = [
            'booking_date' => $date->toDateString(),
            'start_time' => $startTime,
            'service_time' => 1,
        ];

        $bookingService = BookingService::saveBookingService($uuid, $employee, $service, $input);
        $I->assertNotEmpty($bookingService, 'booking service');

        $serviceTime = ServiceTime::find(1);
        $I->assertEquals($serviceTime->id, $bookingService->service_time_id, 'service_time_id');
        $I->assertEquals($serviceTime->length, $bookingService->getEndAt()->diffInMinutes($bookingService->getStartAt()), 'length');
    }

    public function testSaveBookingServiceModifyTime(UnitTester $I)
    {
        $uuid = \Util::uuid();
        $employee = Employee::find(63);
        $service = Service::find(301);
        $date = $this->_getNextDate();
        $startTime = '12:00';
        $input = [
            'booking_date' => $date->toDateString(),
            'start_time' => $startTime,
            'modify_time' => 60,
        ];

        $bookingService = BookingService::saveBookingService($uuid, $employee, $service, $input);
        $I->assertNotEmpty($bookingService, 'booking service');

        $I->assertEquals($service->length + $input['modify_time'],
            $bookingService->getEndAt()->diffInMinutes($bookingService->getStartAt()),
            'length');
    }

    public function testSaveBookingServicePlustime(UnitTester $I)
    {
        $uuid = \Util::uuid();
        $employee = Employee::find(64);
        $service = Service::find(301);
        $date = $this->_getNextDate();
        $startTime = '12:00';
        $input = [
            'booking_date' => $date->toDateString(),
            'start_time' => $startTime,
        ];

        $bookingService = BookingService::saveBookingService($uuid, $employee, $service, $input);
        $I->assertNotEmpty($bookingService, 'booking service');

        $I->assertEquals($service->length + 15,
            $bookingService->getEndAt()->diffInMinutes($bookingService->getStartAt()),
            'length (plus time 15)');
    }

    public function testSaveBookingServiceOverlap(UnitTester $I)
    {
        $user = User::find(70);
        $category = ServiceCategory::find(105);
        $date = $this->_getNextDate();
        $startTime = '12:00';

        // create the first booking (for our booking to overlap)
        $booking = $this->_book($user, $category, $date, $startTime);
        $I->assertNotEmpty($booking, 'booking');

        $uuid = Util::uuid();
        $service = $category->services()->first();

        // the 1 minute overlap should trigger the exception
        $e = null;
        try {
            BookingService::saveBookingService($uuid, $booking->employee, $service, [
                'booking_date' => $booking->date,
                'start_time' => substr(with(clone $booking->getStartAt())->subMinutes($service->length)->addMinute(1)->toTimeString(), 0, 5)
            ]);
        } catch (\Watson\Validating\ValidationException $wvve) {
            $e = $wvve;
        }
        $I->assertNotEmpty($e, 'exception');
    }

    public function testSaveBookingServiceExtraServiceOverlap(UnitTester $I)
    {
        $user = User::find(70);
        $category = ServiceCategory::find(105);
        $date = $this->_getNextDate();
        $startTime = '12:00';

        // create the first booking (for our booking to overlap)
        $booking = $this->_book($user, $category, $date, $startTime);
        $I->assertNotEmpty($booking, 'booking');

        $uuid = Util::uuid();
        $service = $category->services()->first();
        $extraService = $service->extraServices()->first();
        $input = [
            'booking_date' => $booking->date,
            'start_time' => substr(with(clone $booking->getStartAt())->subMinutes($service->length)->toTimeString(), 0, 5)
        ];

        // because of the booking extra service, this booking will overlap with the previous booking
        $bookingExtraService = new BookingExtraService([
            'date' => $booking->date,
            'tmp_uuid' => $uuid,
        ]);
        $bookingExtraService->extraService()->associate($extraService);
        $bookingExtraService->saveOrFail();

        $e = null;
        try {
            BookingService::saveBookingService($uuid, $booking->employee, $service, $input);
        } catch (\Watson\Validating\ValidationException $wvve) {
            $e = $wvve;
        }
        $I->assertNotEmpty($e, 'exception');

        // after deleting the booking extra service, it should work
        $bookingExtraService->forceDelete();
        $bookingService = BookingService::saveBookingService($uuid, $booking->employee, $service, $input);
        $I->assertNotEmpty($bookingService, 'booking service');
    }

    public function testSaveBookingServiceOverlapEmployeeFreetime(UnitTester $I)
    {
        $uuid = \Util::uuid();
        $employee = Employee::find(63);
        $service = Service::find(301);
        $date = $this->_getNextDate();
        $startTime = '12:00';
        $input = [
            'booking_date' => $date->toDateString(),
            'start_time' => $startTime,
        ];

        $freetime = new EmployeeFreetime([
            'date' => $date->toDateString(),
            'start_at' => '12:00:00',
            'end_at' => '17:00:00',
        ]);
        $freetime->user()->associate($employee->user);
        $freetime->employee()->associate($employee);
        $freetime->saveOrFail();

        try {
            BookingService::saveBookingService($uuid, $employee, $service, $input);
        } catch (\Exception $ex) {
            $I->assertTrue($ex instanceof \Watson\Validating\ValidationException, 'Validating exception');
        }
    }

    public function testGetSearchDocument(UnitTester $i)
    {
        $date = new \Carbon\Carbon('2014-12-05 9:00');
        $serviceTime = new ServiceTime([
            'description' => 'Lorem Ipsum'
        ]);

        $model = new BookingService([
            'tmp_uuid'     => 'ABC123',
            'date'         => $date,
            'start_at'     => $date,
            'end_at'       => $date->copy()->addMinutes(45),
            'modify_time'  => 0,
        ]);
        $model->serviceTime = $serviceTime;

        $doc = $model->getSearchDocument();
        $i->assertEquals($doc['tmp_uuid'], 'ABC123');
        $i->assertEquals($doc['date'], '2014-12-05');
        $i->assertEquals($doc['start_at'], '2014-12-05 09:00:00');
        $i->assertEquals($doc['end_at'], '2014-12-05 09:45:00');
        $i->assertEquals($doc['modify_time'], 0);
        $i->assertEquals($doc['service_time'], 'Lorem Ipsum');
    }

    public function testGetSearchMapping(UnitTester $i)
    {
        $date = new \Carbon\Carbon('2014-12-05 9:00');
        $serviceTime = new ServiceTime([
            'description' => 'Lorem Ipsum'
        ]);

        $model = new BookingService([
            'tmp_uuid'     => 'ABC123',
            'date'         => $date,
            'start_at'     => $date,
            'end_at'       => $date->copy()->addMinutes(45),
            'modify_time'  => 0,
        ]);
        $model->serviceTime = $serviceTime;

        $mapping = $model->getSearchDocument();
        $i->assertTrue(array_key_exists('tmp_uuid', $mapping), 'tmp_uuid exists');
        $i->assertTrue(array_key_exists('date', $mapping), 'date exists');
        $i->assertTrue(array_key_exists('start_at', $mapping), 'start_at exists');
        $i->assertTrue(array_key_exists('end_at', $mapping), 'end_at exists');
        $i->assertTrue(array_key_exists('modify_time', $mapping), 'modify_time exists');
        $i->assertTrue(array_key_exists('service_time', $mapping), 'service_time exists');
    }
}
