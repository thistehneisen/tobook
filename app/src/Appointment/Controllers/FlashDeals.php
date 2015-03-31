<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, NAT, Closure, Response;
use Carbon\Carbon;
use App\Core\Models\User;
use App\Consumers\Models\Consumer;
use App\Appointment\Models\Employee;
use App\Appointment\Models\MasterCategory;
use App\Appointment\Models\FlashDeal;
use App\Appointment\Models\FlashDealService;
use App\Appointment\Models\Service;
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

        $longestLength = 0;
        //Check that can create flash deal with a certain service
        foreach ($services as $service) {
            $okForFlashDeal = FlashDeal::canMakeFlashDeal(
                $this->user, $employeeId, $service->id, $bookingDate, $startTime, $service->length
            );
            $okServices[$service->id] = $okForFlashDeal;

            if($okForFlashDeal) {
                $longestLength = $service->length;
            }
        }
        $endTime = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $bookingDate, $startTime))
            ->addMinutes($longestLength);

        return View::make('modules.as.flashdeal.form', [
            'employee'          => $employee,
            'okServices'        => $okServices,
            'services'          => $services,
            'bookingDate'       => $bookingDate,
            'startTime'         => $startTime,
            'endTime'           => $endTime->format('H:i')
        ]);
    }

    public function upsertFlashDeal()
    {
        $employeeId  = (int) Input::get('employee_id');
        $bookingDate = Input::get('start_date');
        $startTime   = Input::get('start_time');
        $endTime     = Input::get('end_time');
        $services    = Input::get('services');
        $percentage  = Input::get('discount_percentage');
        $employee = Employee::ofCurrentUser()->find($employeeId);

        try {
            $flashDeal = new FlashDeal;
            $flashDeal->fill([
                'date' => $bookingDate,
                'start_at' => $startTime,
                'end_at'   => $endTime,
                'discount_percentage' => $percentage
            ]);
            $flashDeal->user()->associate($this->user);
            $flashDeal->employee()->associate($employee);
            $flashDeal->save();

            foreach ($services as $serviceId) {
                $flashDealService = new FlashDealService;
                $service = Service::find($serviceId);
                $flashDealService->service()->associate($service);
                $flashDealService->flashDeal()->associate($flashDeal);
                $flashDealService->save();
            }

            $data['success']     = true;
            $data['baseURl']     = route('as.index');
        } catch(\Exception $ex){
            $data['success'] = false;
            $data['message'] = ($ex instanceof \Watson\Validating\ValidationException)
                ? Util::getHtmlListError($ex)
                : $ex->getMessage();
        }
        return Response::json($data);
    }
}
