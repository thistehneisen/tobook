<?php namespace App\Appointment\Controllers\Embed;

use App;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Employee;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\NAT\CalendarKeeper;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\ServiceTime;
use App\Consumers\Models\Consumer;
use App\Core\Models\User;
use Carbon\Carbon;
use Cart;
use Config;
use DB;
use Hashids;
use Input;
use Redirect;
use Request;
use Response;
use Route;
use Session;
use URL;
use Validator;
use View;

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
            ? $this->getUser($hash)
            : $user;

        $serviceId           = Input::get('service_id');
        $serviceTimeId       = Input::get('service_time');
        $extraServiceIds     = Input::get('extra_services');
        $cartId              = Input::get('cart_id');
        $inhouse             = Input::get('inhouse');
        $isRequestedEmployee = Input::get('is_requested_employee', false);

        $date = (empty(Input::get('date'))) ? Carbon::today() : Input::get('date');
        $consumer = null;
        $cart = null;
        $action = Input::get('action');

        //If carts is empty, user cannot checkout
        if ($action === 'checkout' || $action === 'confirm') {
            if (empty($cartId)) {
                return null;
            }
        }

        if (!empty($cartId)) {
            $cart = Cart::find($cartId);
            if (empty($cart)) {
                return null;
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
        if (!empty($serviceId) && !empty($date)) {
            $service = Service::find($serviceId);

            $serviceTime = (!empty($serviceTimeId))
                ? ServiceTime::find($serviceTimeId)
                : null;

            if (!empty($service)) {
                $employees = $service->employees()->where('is_active', true)->get();
            }
        }
        $extraServices = [];

        if (!empty($extraServiceIds)) {
            $extraServices = ExtraService::whereIn('id', $extraServiceIds)->get();
        }

        $extraServiceLength = $extraServicePrice =  0;

        if (!empty($extraServices)) {
            foreach ($extraServices as $extraService) {
                $extraServiceLength += $extraService->length;
                $extraServicePrice  += $extraService->price;
            }
        }

        //TODO Filter out service without employee
        $categories = ServiceCategory::OfUser($user->id)
            ->orderBy('order')
            ->with(['services' => function ($query) {
                return $query->where('is_active', true)
                    ->with('serviceTimes')
                    ->has('employees');
            }])
            ->where('is_show_front', true)
            ->get();

        $layout = empty($layout)
            ? $this->getLayout()
            : $layout;

        list($priceRange, $hasDiscount, $servicesDiscount) = $this->getDiscountData($categories, $layout);

        $minDay = ((int) $user->asOptions['min_distance']) / 24;
        $minDistance = ($minDay)
            ? sprintf('+%dd', $minDay)
            : 0;

        $maxDistance = ((int) $user->asOptions['max_distance'])
            ? sprintf('+%dd', (int) $user->asOptions['max_distance'])
            : null;

        return [
            'layout'             => $layout,
            'inhouse'            => $inhouse,
            'user'               => $user,
            'categories'         => $categories,
            'priceRange'         => $priceRange,
            'hasDiscount'        => $hasDiscount,
            'servicesDiscount'   => $servicesDiscount,
            'employees'          => $employees,
            'isRequestedEmployee'=> $isRequestedEmployee,
            'cart'               => $cart,
            'consumer'           => $consumer,
            'service'            => $service,
            'serviceId'          => Input::get('serviceId', 0),
            'serviceTime'        => $serviceTime,
            'extraServiceLength' => $extraServiceLength,
            'extraServicePrice'  => $extraServicePrice,
            'extraServices'      => $extraServices,
            'workingTimes'       => $workingTimes,
            'hash'               => $hash,
            'date'               => $date,
            'minDistance'        => $minDistance,
            'maxDistance'        => $maxDistance,
            'action'             => $action,
        ];
    }

    /**
     * Fetch discount data for displaying on CP layout
     *
     * @param Illuminate\Support\Collection $categories
     * @param strinng $layout
     *
     * @return Array
     */
    public function getDiscountData($categories, $layout)
    {
        $priceRange       = [];
        $hasDiscount      = [];
        $servicesDiscount = [];

        if ($layout === 'cp') {
            return [$priceRange, $hasDiscount, $servicesDiscount];
        }

        foreach ($categories as $category) {
            $priceRange[$category->id]  = $category->priceRange;
            $hasDiscount[$category->id] = $category->hasDiscount;
            foreach ($category->services as $service) {
                $servicesDiscount[$service->id] = ($hasDiscount[$category->id] && $service->is_discount_included);
            }
        }

        return [$priceRange, $hasDiscount, $servicesDiscount];
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
            'phone'      => str_replace(' ', '', Input::get('phone')),
        ];

        $validators = [
            'first_name' => ['required'],
            'last_name'  => ['required'],
            'phone'      => ['required', 'numeric'],
        ];

        $extraFields = ['email', 'notes', 'address', 'city', 'country'];
        $user = $this->getUser($hash);

        foreach ($extraFields as $field) {
            if ((int) $user->asOptions[$field] == 3) {
                $fields[$field]     = Input::get($field);
                $fields['country']  = str_replace(trans('common.select'), '', Input::get('country'));
                $validators[$field] = ($field !== 'email') ? ['required'] : ['required', 'email'];
            }
        }

        if ((int) $user->asOptions['email'] == 2) {
            $fields['email']     = Input::get('email');
            $validators['email'] = ['email'];
        }

        if ((int) $user->asOptions['terms_enabled'] == 3) {
            $fields['terms']     = Input::get('terms');
            $validators['terms'] = ['required'];
        }

        return Validator::make($fields, $validators);
    }

    public function getDefaultWorkingTimes($date, $hash = null, $employee = null)
    {
        $user = $this->getUser($hash);
        $workingTimes = CalendarKeeper::getDefaultWorkingTimes($user, $date, false);

        return $workingTimes;
    }

     /**
     * Get table table of an employee
     *
     * @return View
     */
    public function getTimetable()
    {
        $today      = Carbon::today();
        $date       = Input::has('date') ? new Carbon(Input::get('date')) : $today;
        $hash       = Input::get('hash');
        $service    = Service::findOrFail(Input::get('serviceId'));
        $employeeId = (int) Input::get('employeeId');
        $serviceTime = null;

        if (Input::has('serviceTimeId')) {
            if (Input::get('serviceTimeId') !== 'default') {
                $serviceTime = $service->serviceTimes()
                    ->findOrFail(Input::get('serviceTimeId'));
            }
        }

        //Withdrawal time feature
        list($start, $final, $maxWeeks) = $this->getMinMaxDistanceDay($hash);

        if ($date->lt($start)) {
            $date = $start->copy();
        }

        $timetable = [];
        if ($employeeId === -1) {
            $timetable = $this->getTimetableOfAnyone($service, $date, $serviceTime);
        } elseif ($employeeId > 0) {
            $employee = Employee::findOrFail($employeeId);
            $timetable = $this->getTimetableOfSingle($employee, $service, $date, $serviceTime);
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

        $next = ($date->copy()->addDay()->gt($final))
            ? $final
            : $date->copy()->addDay();

        $prev = ($date->copy()->subDay()->lt($final))
            ? $start
            : $date->copy()->subDay();

        return $this->render('timetable', [
            'date'        => $date,
            'startDate'   => $startDate,
            'prev'        => $prev,
            'next'        => $next,
            'final'       => $final,
            'timetable'   => $timetable
        ]);
    }

    /**
     * Generate the timetable of all employees and merge them into one
     * 
     * The point is to generate all possible timeslot of selected service
     * regardless to who serve it. All employee-specific factor need to be
     * validated later
     *
     * @param Service $service
     * @param Carbon  $date
     * @param boolean $showEndTime
     *
     * @return array
     */
    protected function getTimetableOfAnyone(Service $service, Carbon $date, $serviceTime = null, $showEndTime = false, $discount = false)
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
                $showEndTime,
                $discount,
                $timetable
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
     * @param boolean  $showEndTime
     *
     * @return array
     */
    public function getTimetableOfSingle(Employee $employee, Service $service, Carbon $date, $serviceTime = null, $showEndTime = false, $discount = false, $currentTimetable = [])
    {
        $extraServiceIds = Input::get('extraServiceId');
        $extraServices = [];
        if (!empty($extraServiceIds)) {
            foreach ($extraServiceIds as $extraServiceId) {
                $extraService = ExtraService::findOrFail($extraServiceId);
                $extraServices[] = $extraService;
            }
        }
        //isTest = true to show booking in the past
        $isTest = false;

        $timetable = $employee->getTimetable($service, $date, $serviceTime, $extraServices, $showEndTime, $isTest, $discount, $currentTimetable);
        // Sort timetable ascendingly
        ksort($timetable, SORT_STRING);

        return $timetable;
    }

    /**
     * Get discount price of the given service
     *
     * @param Carbon $date
     * @param Carbon $time
     * @param ServiceTime/Service $service
     *
     * @return double
     */
    public function getDiscountPrice($date, $time, $service)
    {
        return $service->getDiscountPrice($date, $time);
    }

    public function getMinMaxDistanceDay($hash)
    {
        $today = Carbon::now();
        $user  = empty($user)
            ? $this->getUser($hash)
            : $user;

        $minDistance = (int) $user->asOptions['min_distance'];
        $maxDistance = (int) $user->asOptions['max_distance']
            ? (int) $user->asOptions['max_distance']
            : 3650;
        $start = $today->copy()->addHours($minDistance);
        $final = $today->copy()->addDays($maxDistance);

        $maxWeeks = (ceil($maxDistance / 7) <= 7)
                    ? ceil($maxDistance / 7)
                    : 7;

        return array($start, $final, $maxWeeks);
    }
}
