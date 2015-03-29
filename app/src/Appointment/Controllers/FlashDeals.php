<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, NAT, Closure, Response;
use Carbon\Carbon;
use App\Core\Models\User;
use App\Consumers\Models\Consumer;
use App\Appointment\Models\Employee;
use App\Appointment\Models\MasterCategory;
use App\Appointment\Models\FlashDeal;
use App\Appointment\Models\Reception\BackendReceptionist;

class FlashDeals extends AsBase
{
    public function getFlashDealForm()
    {
        $employeeId  = (int) Input::get('employee_id');
        $bookingDate = Input::get('booking_date');
        $startTime   = Input::get('start_time');

        $employee = Employee::ofCurrentUser()->find($employeeId);
        $services = $employee->services()
            ->where('is_flash_deal_enabled','=', true)
            ->orderBy('length')->get();

        $okServices = [];

        //Check that can create flash deal with a certain service
        foreach ($services as $service) {
            $okServices[$service->id] = FlashDeal::canMakeFlashDeal(
                $this->user, $employeeId, $service->id, $bookingDate, $startTime, $service->length
            );
        }

        return View::make('modules.as.flashdeal.form', [
            'employee'          => $employee,
            'okServices'        => $okServices,
            'services'          => $services,
            'bookingDate'       => $bookingDate,
            'startTime'         => $startTime
        ]);
    }

    public function upsertFlashDeal()
    {

    }
}
