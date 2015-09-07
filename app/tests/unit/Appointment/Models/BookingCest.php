<?php namespace Test\Appointment\Models;

use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\Resource;
use App\Appointment\Models\ResourceService;
use App\Consumers\Models\Consumer;
use App\Core\Models\User;
use Carbon\Carbon;
use UnitTester;

/**
 * @group as
 */
class BookingCest
{
    use \Test\Traits\Booking;
    use \Test\Traits\Models;

    public function testGetClass(UnitTester $I)
    {
        $booking = new Booking();
        $prefix = 'booked ';

        foreach ([
                    Booking::STATUS_CONFIRM => 'confirmed',
                    Booking::STATUS_PENDING => 'pending',
                    Booking::STATUS_CANCELLED => 'cancelled',
                    Booking::STATUS_ARRIVED => 'arrived',
                    Booking::STATUS_PAID => 'paid',
                    Booking::STATUS_NOT_SHOW_UP => 'not_show_up',
                ] as $status => $text) {
            $booking->status = $status;
            $I->assertEquals($prefix . $text, $booking->getClass());
        }
    }

    public function testGetStatusText(UnitTester $I)
    {
        $booking = new Booking();

        foreach ([
                    Booking::STATUS_CONFIRM => 'confirmed',
                    Booking::STATUS_PENDING => 'pending',
                    Booking::STATUS_CANCELLED => 'cancelled',
                    Booking::STATUS_ARRIVED => 'arrived',
                    Booking::STATUS_PAID => 'paid',
                    Booking::STATUS_NOT_SHOW_UP => 'not_show_up',
                ] as $status => $text) {
            $booking->status = $status;
            $I->assertEquals($text, $booking->getStatusText());
        }
    }

    public function testGetStatusTextAttribute(UnitTester $I)
    {
        $booking = new Booking();

        foreach ([
                    Booking::STATUS_CONFIRM => 'confirmed',
                    Booking::STATUS_PENDING => 'pending',
                    Booking::STATUS_CANCELLED => 'cancelled',
                    Booking::STATUS_ARRIVED => 'arrived',
                    Booking::STATUS_PAID => 'paid',
                    Booking::STATUS_NOT_SHOW_UP => 'not_show_up',
                ] as $status => $text) {
            $booking->status = $status;
            $I->assertEquals(trans('as.bookings.' . $text), $booking->status_text);
        }
    }

    public function testSetStatus(UnitTester $I)
    {
        $booking = new Booking();

        foreach ([
                    Booking::STATUS_CONFIRM => 'confirmed',
                    Booking::STATUS_PENDING => 'pending',
                    Booking::STATUS_CANCELLED => 'cancelled',
                    Booking::STATUS_ARRIVED => 'arrived',
                    Booking::STATUS_PAID => 'paid',
                    Booking::STATUS_NOT_SHOW_UP => 'not_show_up',
                ] as $status => $text) {
            $booking->setStatus($text);
            $I->assertEquals($status, $booking->status);
        }
    }

    public function testGetStartTime(UnitTester $I)
    {
        $booking = new Booking();
        $booking->date = '2014-11-15';
        $booking->start_at = '08:00';

        $I->assertTrue($booking->start_time instanceof \Carbon\Carbon);
        $I->assertEquals($booking->start_time->toTimeString(), '08:00:00');
        $I->assertEquals($booking->start_time->toDateString(), '2014-11-15');
    }

    public function testGetServiceInfo(UnitTester $I)
    {
        $this->initData();

        $booking = $this->_makeABooking();
        $service = $booking->bookingServices()->first()->service;

        $info = $booking->getServiceInfo();

        $I->assertNotEmpty($info, 'getServiceInfo()');
        $I->assertTrue(strpos($info, $service->name) !== false, '$service->name');
        $I->assertTrue(strpos($info, $booking->employee->name) !== false, 'employee_name');
        $I->assertTrue(strpos($info, $booking->date) !== false, 'date');
        $I->assertTrue(strpos($info, $booking->getStartAt()->addMinutes($service->before)->toTimeString()) !== false, 'start_at');
        $I->assertTrue(strpos($info, $booking->getEndAt()->subMinutes($service->after)->toTimeString()) !== false, 'end_at');
    }

    public function testIsBookable(UnitTester $I)
    {
        $this->initData();

        $booking = $this->_makeABooking();

        $I->assertFalse(Booking::isBookable($booking->employee->id, $booking->date,
                $booking->getStartAt(), $booking->getEndAt()),
            'existing booking');

        $I->assertFalse(Booking::isBookable($booking->employee->id, $booking->date,
                $booking->getStartAt()->subMinutes(15), $booking->getStartAt()->addMinutes(15)),
            'existing booking, before 15 minutes, 30 overlap');

        $I->assertFalse(Booking::isBookable($booking->employee->id, $booking->date,
                $booking->getEndAt()->subMinutes(15), $booking->getEndAt()->addMinutes(15)),
            'existing booking, after 15 minutes, 30 overlap');

        $I->assertFalse(Booking::isBookable($booking->employee->id, $booking->date,
                $booking->getStartAt()->subMinutes(15), $booking->getEndAt()->addMinutes(15)),
            'existing booking, +15 minutes each way');

        //Check overlappin end time and start time 15 mins (10:30:11:30 and 11:15:12:00 for example)
        $newStartTime = $booking->getEndAt()->subMinutes(15);
        $newEndTime = $newStartTime->copy()->addMinutes($booking->total);
        $I->assertTrue($newStartTime < $booking->getEndAt(), 'New start time is before old end time');
        $I->assertFalse(Booking::isBookable($booking->employee->id, $booking->date,
                $newStartTime, $newEndTime), 'existing booking, overlapp 15 minutes');

        $I->assertTrue(Booking::isBookable($booking->employee->id, $booking->date,
                $booking->getStartAt(), $booking->getEndAt(), $booking->uuid),
            'existing booking, check for itself');

        $booking->status = Booking::STATUS_CANCELLED;
        $booking->save();

        $I->assertTrue(Booking::isBookable($booking->employee->id, $booking->date,
            $booking->getStartAt(), $booking->getEndAt()), 'existing booking canceled');
    }

    public function testGetLastestBookingEndTime(UnitTester $I)
    {
        $user = User::find(70);
        $date = $this->_getNextDate();

        $latestBooking = Booking::getLastestBookingEndTime($date->toDateString(), $user);
        $I->assertEmpty($latestBooking, 'no booking yet');

        $booking = $this->_makeABooking($date);
        $latestBooking = Booking::getLastestBookingEndTime($date->toDateString(), $booking->user);
        $I->assertNotEmpty($latestBooking, 'has one booking');
        $I->assertEquals($booking->uuid, $latestBooking->uuid, '$booking->uuid');

        $booking2 = $this->_makeABooking($date, '08:00');
        $latestBooking = Booking::getLastestBookingEndTime($date->toDateString(), $booking2->user);
        $I->assertNotEmpty($latestBooking, 'has two bookings');
        $I->assertNotEquals($booking2->uuid, $latestBooking->uuid, '$booking2->uuid');
        $I->assertEquals($booking->uuid, $latestBooking->uuid, '$booking->uuid');

        $booking3 = $this->_makeABooking($date, '15:00');
        $latestBooking = Booking::getLastestBookingEndTime($date->toDateString(), $booking3->user);
        $I->assertNotEmpty($latestBooking, 'has three bookings');
        $I->assertEquals($booking3->uuid, $latestBooking->uuid, '$booking3->uuid');
    }

    public function testGetLastestBookingEndTimeInRange(UnitTester $I)
    {
        $user = User::find(70);
        $I->amLoggedAs($user);

        $date1 = $this->_getNextDate();
        $date2 = with(clone $date1)->addDay(1);

        $booking1 = $this->_makeABooking($date1);
        $booking2 = $this->_makeABooking($date2);

        $lastestBooking = Booking::getLastestBookingEndTimeInRange($date1->toDateString(), $date1->toDateString());
        $I->assertNotEmpty($lastestBooking, 'within the first day');
        $I->assertEquals($booking1->uuid, $lastestBooking->uuid, '$booking1->uuid');

        $lastestBooking = Booking::getLastestBookingEndTimeInRange($date1->toDateString(), $date2->toDateString());
        $I->assertNotEmpty($lastestBooking, 'within two days');
        $I->assertEquals($booking2->uuid, $lastestBooking->uuid, '$booking2->uuid');
    }

    public function testGetExtraServices(UnitTester $I)
    {
        $booking = $this->_makeABooking();

        $extraServices = $booking->getExtraServices();
        $I->assertNotEmpty($extraServices, 'extra services');

        foreach ($booking->extraServices as $bookingExtraService) {
            $I->assertTrue(in_array($bookingExtraService->extraService->name, $extraServices), $bookingExtraService->extraService->name);
        }
    }

    public function testSaveBookingSuccess(UnitTester $I)
    {
        $uuid = Booking::uuid();
        $user = User::find(70);
        $employee = Employee::find(63);
        $service = Service::find(301);
        $consumer = Consumer::find(1);
        $date = $this->_getNextDate();
        $startTime = '12:00';
        $input = [
            'booking_date' => $date->toDateString(),
            'start_time' => $startTime,
        ];

        $bookingService = BookingService::saveBookingService($uuid, $employee, $service, $input);
        $total = $bookingService->calculateServiceLength();
        $plusTime = $bookingService->plus_time;

        foreach ($service->extraServices as $extraService) {
            BookingExtraService::addExtraService($uuid, $employee, $bookingService, $extraService);
            $total += $extraService->length;
        }

        $booking = Booking::saveBooking($uuid, $user, $consumer, $input);

        if ($I !== null) {
            $I->assertEquals($uuid, $booking->uuid, 'uuid');
            $I->assertEquals($user->id, $booking->user_id, 'user_id');
            $I->assertEquals($consumer->id, $booking->consumer_id, 'consumer_id');
            $I->assertEquals($employee->id, $booking->employee_id, 'employee_id');
            $I->assertEquals($date->toDateString(), $booking->date, 'date');
            $I->assertEquals($total, $booking->total, 'total');
            $I->assertEquals(0, $booking->modify_time, 'modify_time');
            $I->assertEquals($plusTime, $booking->plus_time, 'plus_time');
            $I->assertEquals(substr($startTime, 0, 5), substr($booking->start_at, 0, 5), 'start_at');
            $I->assertEquals(Booking::STATUS_CONFIRM, $booking->status, 'status');
        }
    }

    public function testSaveBookingCancel(UnitTester $I)
    {
        $booking = $this->_makeABooking();
        $consumer = Consumer::find($booking->consumer_id);

        $e = null;
        try {
            Booking::saveBooking($booking->uuid, $booking->user, $consumer, [
                'status' => 'cancelled',
            ]);
        } catch (\Watson\Validating\ValidationException $wvve) {
            $e = $wvve;
        }
        $I->assertNotNull($e, 'exception');

        $saved = Booking::saveBooking($booking->uuid, $booking->user, $consumer, [
            'status' => 'cancelled',
        ], $booking);

        $I->assertNotEmpty($saved, 'saved');

        $booking = Booking::find($booking->id);
        $I->assertEquals(Booking::STATUS_CANCELLED, $booking->status, 'status');
    }

    public function testSaveBookingNoServices(UnitTester $I)
    {
        $uuid = Booking::uuid();
        $user = User::find(70);
        $consumer = Consumer::find(1);
        $input = [];

        $e = null;
        try {
            Booking::saveBooking($uuid, $user, $consumer, $input);
        } catch (\Watson\Validating\ValidationException $wvve) {
            $e = $wvve;
        }
        $I->assertNotNull($e, 'exception');
    }

    public function testRescheduleBooking(UnitTester $I)
    {
        $booking = $this->_makeABooking();
        $input = [
            'booking_date' => $booking->date,
            'start_time' => '08:00',
        ];

        $I->assertFalse(Booking::isBookable($booking->employee->id, $booking->date,
            $booking->getStartAt(), $booking->getEndAt()),
            'not bookable before reschedule');

        $rescheduled = Booking::rescheduleBooking($booking, $booking->employee, $input);

        $I->assertNotNull($rescheduled, 'rescheduled');
        $I->assertEquals($input['booking_date'], $rescheduled->date, 'date');
        $I->assertNotEquals($booking->start_at, $rescheduled->start_at, 'start_at');
        $I->assertEquals(substr($input['start_time'], 0, 5), substr($rescheduled->start_at, 0, 5), 'start_at');

        $I->assertTrue(Booking::isBookable($booking->employee->id, $booking->date,
            $booking->getStartAt(), $booking->getEndAt()),
            'bookable after reschedule');
    }

    private function _makeABooking(Carbon $date = null, $startTime = '12:00')
    {
        $user = User::find(70);
        $serviceCategory = ServiceCategory::find(105);

        return $this->_book($user, $serviceCategory, $date, $startTime, $this->employee);
    }

    public function testBookingWithServiceResources(UnitTester $I)
    {
        $user = User::find(70);
        $service = Service::find(301);
        $employee1 = Employee::find(63);
        $employee2 = Employee::find(64);

        // setup resource
        $resource = new Resource();
        $resource->fill(['name'=> 'Resource 1']);
        $resource->user()->associate($user);
        $resource->save();
        $resourceService = new ResourceService();
        $resourceService->service()->associate($service);
        $resourceService->resource()->associate($resource);
        $resourceService->save();

        $date = with(new \Carbon\Carbon())->year(2014)->month(11)->day(10);
        foreach ([$employee1, $employee2] as $employee) {
            $uuid = Booking::uuid();

            // try only the part can fail
            try {
                $bookingService = BookingService::saveBookingService($uuid, $employee, $service, [
                    'booking_date' => $date->toDateString(),
                    'start_time'   => '10:00',
                    'modify_time'  => 0,
                ]);
            } catch (\Exception $ex) {
                // this should fail with employee2 only
                $I->assertEquals($employee, $employee2);
                $I->assertEquals(trans('as.bookings.error.not_enough_resources'), $ex->getMessage());
            }
        }
    }

    public function testCalculateCommissions(UnitTester $i)
    {
        $user = User::find(70);
        $data = [
            ['total' => 22, 'uuid' => uniqid(), 'status' => \App\Appointment\Models\Booking::STATUS_PAID],
            ['total' => 20, 'uuid' => uniqid(), 'status' => \App\Appointment\Models\Booking::STATUS_PAID],
        ];

        foreach ($data as $input) {
            $booking = new Booking($input);
            $booking->user()->associate($user);
            $booking->save();
        }

        $i->assertEquals(12.6, Booking::calculateCommissions($user));
    }
}
