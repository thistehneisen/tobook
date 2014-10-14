<?php namespace App\Appointment\Controllers\Embed;
use App, Input, Config, Redirect, Route, View, Validator, Request, Response, DB;
use Hashids, Session, URL;
use Carbon\Carbon;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeService;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\AsConsumer;
use App\Consumers\Models\Consumer;
use App\Core\Models\Cart;
use App\Core\Models\CartDetail;
use App\Core\Models\User;
use App\Appointment\Controllers\AsBase;

trait Layout
{
    /**
     * Display the booking form of provided user
     *
     * @param string $hash UserID hashed
     *
     * @return View
     */
    public function handleIndex($hash, $user = null, $layout = null)
    {
        $user = empty($user)
            ?   $this->getUser($hash)
            : $user;

        $serviceId       = Input::get('service_id');
        $serviceTimeId   = Input::get('service_time');
        $extraServiceIds = Input::get('extra_services');
        $cartId          = Input::get('cart_id');

        $date = (empty(Input::get('date'))) ? Carbon::today() : Input::get('date');
        $consumer = null;
        $cart = null;
        $action = Input::get('action');

        //If carts is empty, user cannot checkout
        if($action === 'checkout' || $action === 'confirm'){
            if(empty($cartId)){
                return Redirect::route('as.embed.embed', ['hash' => $hash]);
            }
        }

        if(!empty($cartId)){
            $cart = Cart::find($cartId);
            if (empty($cart)) {
                return Redirect::route('as.embed.embed', ['hash' => $hash]);
            }
        }

        if (!$date instanceof Carbon) {
            try {
                $date = new Carbon($date);
            } catch (\Exception $ex) {
                $date = Carbon::today();
            }
        }

        $employees= [];
        $service  = null;
        $serviceTime = null;

        //TODO get default workingTimes from config
        $workingTimes = $this->getDefaultWorkingTimes($date, $hash);
        //for select employee view
        if(!empty($serviceId) && !empty($date)){
            $service = Service::find($serviceId);

            $serviceTime = (!empty($serviceTimeId))
                ? ServiceTime::find($serviceTimeId)
                : null;

            $employees = $service->employees()->where('is_active', true)->get();
        }
        $extraServices = [];
        if(!empty($extraServiceIds)){
            $extraServices = ExtraService::whereIn('id', $extraServiceIds)->get();
        }
        $extraServiceLength = $extraServicePrice =  0;
        if(!empty($extraServices)){
            foreach ($extraServices as $extraService) {
                $extraServiceLength += $extraService->length;
                $extraServicePrice  += $extraService->price;
            }
        }

        $categories = ServiceCategory::OfUser($user->id)
            ->orderBy('order')
            ->with(['services' => function ($query) {
                return $query->where('is_active', true)->with('serviceTimes');
            }])->where('is_show_front', true)
            ->get();

        $layout = empty($layout)
            ? $this->getLayout()
            : $layout;

        return [
            'layout'             => $layout,
            'user'               => $user,
            'categories'         => $categories,
            'employees'          => $employees,
            'cart'               => $cart,
            'consumer'           => $consumer,
            'service'            => $service,
            'serviceTime'        => $serviceTime,
            'extraServiceLength' => $extraServiceLength,
            'extraServicePrice'  => $extraServicePrice,
            'extraServices'      => $extraServices,
            'workingTimes'       => $workingTimes,
            'hash'               => $hash,
            'date'               => $date,
            'action'             => $action
        ];
    }

    /**
     * Get layout for this embed
     *
     * @return int
     */
    protected function getLayout()
    {
        $layoutId = (int) Input::get('l');
        if (!$layoutId) {
            $layoutId = 1;
        }

        return 'layout-'.$layoutId;
    }

    /**
     * Get the User object associated to this embed
     *
     * @param string $hash
     *
     * @return App\Core\Models\User
     */
    protected function getUser($hash = null)
    {
        $hash = $hash ?: Input::get('hash');
        if ($hash === null) {
            return App::abort(404);
        }

        $decoded = Hashids::decrypt($hash);
        if (empty($decoded[0])) {
            return App::abort(404);
        }

        return User::findOrFail($decoded[0]);
    }

    protected function getConfirmationValidator()
    {
        $hash   = Input::get('hash');

        $fields = [
            'first_name' => Input::get('first_name'),
            'last_name'  => Input::get('last_name'),
            'phone'      => Input::get('phone'),
            'email'      => Input::get('email'),
        ];

        $validators = [
            'first_name' => ['required'],
            'last_name'  => ['required'],
            'phone'      => ['required'],
            'email'      => ['required', 'email'],
        ];

        $extraFields = ['notes', 'address', 'city', 'country'];
        $user = $this->getUser($hash);

        foreach ($extraFields as $field) {
            if ((int)$user->asOptions[$field] == 3) {
                $fields[$field]     = Input::get($field);
                $fields['country']  = str_replace(trans('common.select'), '',Input::get('country'));
                $validators[$field] = ['required'];
            }
        }

        return Validator::make($fields, $validators);
    }

    public function getDefaultWorkingTimes($date, $hash = null)
    {
        $user = $this->getUser($hash);
        $workingTimes = $user->getASDefaultWorkingTimes($date);
        return $workingTimes;
    }

     /**
     * Get table table of an employee
     *
     * @return View
     */
    public function getTimetable()
    {
        $today    = Carbon::today();
        $date     = Input::has('date') ? new Carbon(Input::get('date')) : $today;
        $hash     = Input::get('hash');
        $service  = Service::findOrFail(Input::get('serviceId'));

        if ($date->lt($today)) {
            $date = $today->copy();
        }

        $employeeId = (int) Input::get('employeeId');
        if ($employeeId === -1) {
            $timetable = $this->getTimetableOfAnyone($service, $date);
        } elseif ($employeeId > 0) {
            $employee = Employee::findOrFail($employeeId);
            $timetable = $this->getTimetableOfSingle($employee, $service, $date);
        }

        $i = 1;
        $startDate = $date->copy();
        while ($i <= 2) {
            $startDate = $startDate->subDay();

            if ($startDate->lt($today)) {
                $startDate = $today->copy();
                break;
            }
            $i++;
        }

        return $this->render('timetable', [
            'date'      => $date,
            'startDate' => $startDate,
            'prev'      => $date->copy()->subDay(),
            'next'      => $date->copy()->addDay(),
            'timetable' => $timetable
        ]);
    }

    /**
     * The the timetable of all employees and merge them into one
     *
     * @param Service $service
     * @param Carbon  $date
     * @param boolean $showEndTime
     *
     * @return array
     */
    protected function getTimetableOfAnyone(Service $service, Carbon $date, $serviceTime = null, $showEndTime = false)
    {
        $timetable = [];
        // Get timetable of all employees
        $user = $this->getUser();
        $employees = $this->getEmployeesOfService($service);
        foreach ($employees as $employee) {
            $data = $this->getTimetableOfSingle(
                $employee,
                $service,
                $date,
                $serviceTime,
                $showEndTime
            );

            foreach ($data as $time => $_) {
                if (!isset($timetable[$time])) {
                    $timetable[$time] = $employee;
                }
            }
        }

        // Sort timetable ascendingly
        ksort($timetable, SORT_STRING);

        return $timetable;
    }

    /**
     * Get timetable of a single employee
     *
     * @param Employee $employee
     * @param Service  $service
     * @param Carbon   $date
     * @param boolean $showEndTime
     *
     * @return array
     */
    protected function getTimetableOfSingle(Employee $employee, Service $service, Carbon $date, $serviceTime = null, $showEndTime = false)
    {
        $timetable = [];
        $workingTimes = $this->getDefaultWorkingTimes($date, Input::get('hash'));

        foreach ($workingTimes as $hour => $minutes) {
            foreach ($minutes as $shift) {
                // We will check if this time bookable
                $startTime = $date->copy()->hour($hour)->minute($shift);
                $endTime = $startTime->copy()->addMinutes($service->length);

                $isBookable = Booking::isBookable(
                    $employee->id,
                    $date,
                    $startTime,
                    $endTime
                );

                // If not, move to the next one
                if ($isBookable === false) {
                    continue;
                }

                if ($startTime->gt(Carbon::now())) {
                    $formatted = sprintf('%02d:%02d', $hour, $shift);

                    if ($showEndTime) {
                        $length = ($serviceTime !== null)
                            ? $serviceTime->length
                            : $service->length;

                        $startTime->addMinutes($length);
                        $formatted .= sprintf(' - %02d:%02d', $startTime->hour, $startTime->minute);
                    }

                    $timetable[$formatted] = $employee;
                }
            }
        }

        // Sort timetable ascendingly
        ksort($timetable, SORT_STRING);

        return $timetable;
    }
}
