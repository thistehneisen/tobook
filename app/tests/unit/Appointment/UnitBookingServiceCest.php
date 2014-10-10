<?php namespace Appointment\Models;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Core\Models\User;
use App\Core\Models\Role;
use Carbon\Carbon;
use \UnitTester;
use Util;

/**
 * @group as
 */
class UnitBookingServiceCest
{
    public function testBookingService(UnitTester $t)
    {
        $uuid = Util::uuid();
        $service = Service::find(301);
        $bookingService = new BookingService;
        $bookingService->fill([
            'tmp_uuid' => $uuid,
            'date' => Carbon::now(),
            'start_at' => '08:00',
            'end_at'   => '08:45',
            'modify_time' => 0
        ]);
        $bookingService->user()->associate(User::find(70));
        $bookingService->service()->associate($service);
        $bookingService->employee()->associate(Employee::find(63));
        $bookingService->save();

        $t->assertEquals($service->name, 'Klassinen hieronta');
        $t->assertEquals($service->length, 45);
        $t->assertEquals($service->after, 15);
        $t->assertEquals($bookingService->tmp_uuid, $uuid);
        $t->assertEquals($bookingService->getCartStartAt()->toTimeString(), '08:00:00');
        $t->assertEquals($bookingService->getCartEndAt()->toTimeString(), '08:30:00');
    }
}
