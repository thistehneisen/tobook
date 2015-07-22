<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Util, Response, Request, Validator, DB, NAT;
use Carbon\Carbon;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeDefaultTime;
use App\Appointment\Models\EmployeeFreetime;
use App\Appointment\Models\EmployeeCustomTime;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Service;
use App\Appointment\Models\CustomTime;

class Employees extends AsBase
{
    use \CRUD;
    protected $viewPath = 'modules.as.employees';
    protected $crudOptions = [
        'modelClass' => 'App\Appointment\Models\Employee',
        'langPrefix' => 'as.employees',
        'layout' => 'modules.as.layout',
        'bulkActions' => [],
        'sortable' => true,
        'indexFields' => ['avatar', 'name', 'email', 'phone', 'is_active'],
        'presenters' => [
            'avatar' => ['App\Appointment\Controllers\Employees', 'presentAvatar'],
        ],
    ];

    public static function presentAvatar($value, $item)
    {
        return '<img src="' . $item->getAvatarUrl() . '" alt="" class="img-rounded" />';
    }

    /**
     * {@inheritdoc}
     */
    public function upsert($id = null)
    {
        $services = Service::ofCurrentUser()->lists('name', 'id');
        $employee = ($id !== null)
            ? Employee::ofCurrentUser()->findOrFail($id)
            : new Employee();

        return $this->render('form', [
            'services' => $services,
            'employee' => $employee,
            'employeeId' => $employee->id
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function upsertHandler($item)
    {
        // Fill general information
        $item->fill([
            'name'                            => Input::get('name'),
            'email'                           => Input::get('email'),
            'phone'                           => Input::get('phone'),
            'description'                     => Input::get('description'),
            'is_subscribed_email'             => Input::get('is_subscribed_email', false),
            'is_subscribed_sms'               => Input::get('is_subscribed_sms', false),
            'is_received_calendar_invitation' => Input::get('is_received_calendar_invitation', false),
            'is_active'                       => Input::get('is_active'),
            'status'                          => Input::get('status'),
            'business_id'                     => Input::get('business_id'),
            'account'                         => Input::get('account'),
        ]);

        $status      = Input::get('status');
        $business_id = Input::get('business_id');
        $account     = Input::get('account');

        if((int)$status === Employee::STATUS_FREELANCER) {
            $validator = Employee::getFreelancerValidator($business_id, $account);
            if($validator->fails()) {

            }
        }

        // Update data
        if (Input::hasFile('avatar')) {
            $file            = Input::file('avatar');
            $destinationPath = public_path(Config::get('varaa.upload_folder')).'/images';
            $filename = sprintf('%s_%s.%s', Carbon::now()->format('YmdHis'), str_random(12), $file->getClientOriginalExtension());
            $uploadSuccess   = $file->move($destinationPath, $filename);
            if ($uploadSuccess) {
                $item->avatar = $filename;
            }
        }

        // Attach this employee to the current user
        $item->user()->associate($this->user);
        $item->saveOrFail();

        // Sync selected services
        $services = Input::get('services');
        if (!empty($services)) {
            $item->services()->sync($services);
        } else {
            // Maybe they want to remove all services
            $item->services()->detach();
        }

        return $item;
    }

    /**
     * Show the form to edit default working time of an employee
     *
     * @param int $id Employee's ID
     *
     * @return View
     */
    public function defaultTime($id)
    {
        $employee = Employee::ofCurrentUser()->find($id);
        $defaultTime = $employee->getDefaultTimes();

        return $this->render('defaultTime', [
            'defaultTime' => $defaultTime,
            'employee'    => $employee
        ]);
    }

    /**
     * Update default time of an employee
     *
     * @return Redirect
     */
    public function updateDefaultTime()
    {
        $employeeId = Input::get('employee_id');
        $employee = Employee::ofCurrentUser()->find($employeeId);

        $types = EmployeeDefaultTime::getTypes();
        $input = Input::all();
        try {
            foreach ($types as $type) {
                $startAt = Carbon::createFromTime(
                    $input['start_hour'][$type],
                    $input['start_minute'][$type],
                    0,
                    Config::get('app.timezone')
                );
                $endAt   = Carbon::createFromTime(
                    $input['end_hour'][$type],
                    $input['end_minute'][$type],
                    0,
                    Config::get('app.timezone')
                );

                $isDayOff = false;
                if (isset($input['is_day_off'][$type])) {
                    $isDayOff  = (bool) $input['is_day_off'][$type];
                }
                $data = [
                    'type'       => $type,
                    'start_at'   => $startAt,
                    'end_at'     => $endAt,
                    'is_day_off' => $isDayOff
                ];

                $defaultTime  = EmployeeDefaultTime::where('employee_id', $employeeId)
                    ->where('type',$type)
                    ->first();

                if (empty($defaultTime)) {
                    $defaultTime = new EmployeeDefaultTime();
                }
                $defaultTime->fill($data);
                $defaultTime->employee()->associate($employee);
                $defaultTime->save();
            }

            // We need to rebuild the NAT calendar
            NAT::enqueueToRebuild($this->user);
        } catch (\Watson\Validating\ValidationException $ex) {
            return Redirect::route('as.services.categories')
                ->with('messages', $this->successMessageBag($ex->getErrors()));
        }

        return Redirect::route('as.employees.defaultTime.get', ['id' => $employeeId])
            ->with('messages', $this->successMessageBag(
                trans('as.employees.success_save_default_time')
            ));
    }

    /**
     * Display form for adding freetime from BE calendar
     */
    public function getFreeTimeForm()
    {
        $employeeId  = Input::get('employee_id');
        $bookingDate = Input::get('booking_date');
        $startTime   = Input::get('start_time');
        $endTime     = with(new Carbon($startTime))->addMinutes(60);
        $employee    = Employee::ofCurrentUser()->find($employeeId);
        $employees   = Employee::ofCurrentUser()->lists('name','id');

        //TODO get form settings or somewhere else
        $workingTimes = range(6, 22);
        $workShift = range(0, 45, 15);
        $times = [];
        foreach ($workingTimes as $hour) {
           foreach (range(0, 45, 15) as $minuteShift) {
                $time = sprintf('%02d:%02d', $hour, $minuteShift);
                $times[$time] = $time;
           }
        }

        return View::make('modules.as.employees.freetimeForm', [
            'employees'   => $employees,
            'employee'    => $employee,
            'bookingDate' => $bookingDate,
            'startTime'   => $startTime,
            'endTime'     => $endTime->format('H:i'),
            'times'       => $times
        ]);
    }

    /**
     * Handle ajax request to add new freetime from BE calendar
     */
    public function addEmployeeFreeTime()
    {
        $employeeId = Input::get('employees');
        $data = [];
        try {
            $startAt     = new Carbon(Input::get('start_at'));
            $endAt       = new Carbon(Input::get('end_at'));
            $fromDate    = new Carbon(Input::get('from_date'));
            $toDate      = new Carbon(Input::get('to_date'));
            $description = Input::get('description');

            if($fromDate->gt($toDate)) {
                throw new \Exception(trans('as.employees.error.from_date_greater_than_to_date'), 1);

            }
            $days = (int) $fromDate->diffInDays($toDate)+1;
            $bookings = array();
            for($day = 0; $day < $days; $day++) {
                $date = $fromDate->copy()->addDays($day);
                $overlaps = Booking::getOverlappedBookings($employeeId, $date, $startAt, $endAt);
                foreach ($overlaps as $booking) {
                    $bookings[] = $booking;
                }
            }

            //Checking if freetime overlaps with any booking or not
            if(!empty($bookings)) {
                $data['success'] = false;
                $data['message'] = trans('as.employees.error.freetime_overlapped_with_booking');
                $data['message'] .= '<ul>';
                foreach ($bookings as $booking) {
                    $data['message'] .= '<li>' . $booking->startTime->toDateTimeString() . '</li>';
                }
                $data['message'] .= '</ul>';
                return Response::json($data);
            }

            for ($day = 0; $day < $days; $day++) {
                $employeeFreetime = new EmployeeFreetime();
                $date = $fromDate->copy()->addDays($day);
                $employeeFreetime->fill([
                    'date' => $date->toDateString(),
                    'start_at' => $startAt->toTimeString(),
                    'end_at' => $endAt->toTimeString(),
                    'description' => $description
                ]);

                $employee = Employee::ofCurrentUser()->find($employeeId);
                $employeeFreetime->user()->associate($this->user);
                $employeeFreetime->employee()->associate($employee);
                $employeeFreetime->save();
                $data['success'] = true;
                // Remove NAT slots since employee has freetime
                NAT::removeEmployeeFreeTime($employeeFreetime);
            }
        } catch (\Exception $ex) {
            $data['success'] = false;
            $data['message'] = $ex->getMessage();
        }

        return Response::json($data);
    }

    /**
     * Handle ajax request to delete freetime from BE calendar
     */
    public function deleteEmployeeFreeTime()
    {
        $freetimeId = Input::get('freetime_id');
        $data = [];
        try {
            $freetime = EmployeeFreetime::findOrFail($freetimeId);

            // Remove NAT slots since employee has freetime
            NAT::restoreEmployeeFreeTime($freetime);

            $freetime->delete();
            $data['success'] = true;
        } catch (\Exception $ex) {
            $data['success'] = false;
            $data['message'] = $ex->getMessage();
        }

        return Response::json($data);
    }

    /**
     * Display custom time of a employee
     * Handle insert custom time for employee
     */
    public function customTime()
    {
        $fields      = with(new CustomTime())->fillable;
        $customTimes = CustomTime::ofCurrentUser()
            ->orderBy('start_at')
            ->orderBy('end_at')
            ->get();

        return $this->render('customTime.index', [
            'items'      => $customTimes,
            'fields'     => $fields,
            'langPrefix' => $this->crudOptions['langPrefix'],
            'now'        => Carbon::now()
        ]);
    }

    /**
     * Show the form to edit employee custom time
     *
     * @param int $id
     * @param int $customTimeId
     *
     * @return View
     */
    public function upsertCustomTime($customTimeId)
    {
        $customTime = CustomTime::ofCurrentUser()->find($customTimeId);

        return $this->render('customTime.upsert', [
            'customTime' => $customTime,
            'now'        => Carbon::now()
        ]);
    }

    /**
     * Add/edit custom time of an employee
     *
     * @param int $id EmployeeId
     *
     * @return Redirect
     */
    public function doUpsertCustomTime($customTimeId = null)
    {
        try {
            $message = ($customTimeId !== null)
                ? trans('as.crud.success_edit')
                : trans('as.crud.success_add');

            // Check duplicate
            $customTime = CustomTime::ofCurrentUser()->find($customTimeId);

            if ($customTime === null) {
                $customTime = ($customTimeId === null)
                    ? new CustomTime
                    : CustomTime::ofCurrentUser()->find($customTimeId);
            } else {
                $message = trans('as.crud.success_edit');
            }

            $customTime->fill([
                'name'       => Input::get('name'),
                'start_at'   => Input::get('start_at'),
                'end_at'     => Input::get('end_at'),
                'is_day_off' => Input::get('is_day_off', false),
            ]);
            $customTime->user()->associate($this->user);
            $customTime->save();

            return Redirect::route('as.employees.customTime')
                ->with(
                    'messages',
                    $this->successMessageBag($message)
                );
        } catch (\Watson\Validating\ValidationException $ex) {
            return Redirect::back()->withInput()->withErrors($ex->getErrors());
        }
    }

    public function deleteCustomTime($id)
    {
        $customTime = CustomTime::ofCurrentUser()->find($id)->delete();

        EmployeeCustomTime::where('custom_time_id', $id)->delete();

        return Redirect::route('as.employees.customTime')
            ->with(
                'messages',
                $this->successMessageBag(trans('as.crud.success_delete'))
            );
    }

    /**
     * View all workshift plan of all employee in a selected month
     *
     * @return view
     */
    public function workshiftPlanning()
    {
        $current = Carbon::now();

        $startOfMonth = Input::has('start')
            ? new Carbon(Input::get('start'))
            : $current->copy()->startOfMonth();
        $endOfMonth = Input::has('end')
            ? new Carbon(Input::get('end'))
            : $current->copy()->endOfMonth();

        $employees       = Employee::ofCurrentUser()->get();
        $customTimesList = [];
        $sarturdayHours  = [];
        $sundayHours     = [];
        $montlyHours     = [];

        $customTimes = CustomTime::ofCurrentUser()
            ->orderBy('start_at')
            ->orderBy('end_at')
            ->select(
                DB::raw(
                    'CONCAT(name, " (",
                    TIME_FORMAT(start_at, "%H:%i"), " - ",
                    TIME_FORMAT(end_at, "%H:%i"),")") AS name'
                ),
                'id'
                )
            ->lists('name', 'id');

        $customTimes = [0 => trans('common.options_select')] + $customTimes;
        $customTimeWeekSummary = [];
        $customTimeMonthSummary = [];
        foreach ($employees as $employee) {
            $items = $employee->employeeCustomTimes()
                ->with('customTime')
                ->where('date','>=', $startOfMonth)
                ->where('date','<=', $endOfMonth)
                ->orderBy('date','asc')->get();

            $sarturdayHours[$employee->id] = 0;
            $sundayHours[$employee->id]    = 0;
            $montlyHours[$employee->id]    = 0;

            foreach ($items as $item) {
                $customTimesList[$item->date][$employee->id] = $item;
                $carbonDate = new Carbon($item->date);

                //Collect weekly summary data for each employee
                if(empty($customTimeWeekSummary[$carbonDate->weekOfYear][$employee->id])) {
                    $customTimeWeekSummary[$carbonDate->weekOfYear][$employee->id] = $item->getWorkingHours();
                } else {
                    $customTimeWeekSummary[$carbonDate->weekOfYear][$employee->id] += $item->getWorkingHours();
                }

                //Collect monthly summary data for each employee
                if(empty($customTimeMonthSummary[$carbonDate->month][$employee->id])) {
                    $customTimeMonthSummary[$carbonDate->month][$employee->id] = $item->getWorkingHours();
                } else {
                    $customTimeMonthSummary[$carbonDate->month][$employee->id] += $item->getWorkingHours();
                }

                $dayOfWeek = $item->getDayOfWeek();

                if ($dayOfWeek == Carbon::SATURDAY) {
                    $sarturdayHours[$employee->id] += $item->getWorkingHours();
                } elseif ($dayOfWeek == Carbon::SUNDAY) {
                    $sundayHours[$employee->id] += $item->getWorkingHours();
                }
                $montlyHours[$employee->id] += $item->getWorkingHours();
            }
        }

        $currentMonths = [];
        $startDay      = $startOfMonth->copy();
        $days = $startDay->copy()->diffInDays($endOfMonth);

        foreach (range(1, $days+1) as $day) {
            if (!empty($customTimesList[$startDay->toDateString()])) {
                $currentMonths[$startDay->toDateString()]['date'] = $startDay->copy();
                $currentMonths[$startDay->toDateString()]['employees'] = $customTimesList[$startDay->toDateString()];
            } else {
                $currentMonths[$startDay->toDateString()]['date'] = $startDay->copy();
                $currentMonths[$startDay->toDateString()]['employees'] = [];
            }
            $startDay->addDay();
        }

        return $this->render('workshiftPlanning', [
            'customTimesList'        => $customTimesList,//custom time of the employee
            'employees'              => $employees,
            'current'                => $current,
            'currentMonths'          => $currentMonths,
            'startOfMonth'           => $startOfMonth,
            'endOfMonth'             => $endOfMonth,
            'sarturdayHours'         => $sarturdayHours,
            'sundayHours'            => $sundayHours,
            'montlyHours'            => $montlyHours,
            'customTimeWeekSummary'  => $customTimeWeekSummary,
            'customTimeMonthSummary' => $customTimeMonthSummary,
            'customTimes'            => json_encode($customTimes)
        ]);
    }

    /**
     * Update employee workshift on workshift summary table via ajax request
     * @return array
     */
    public function updateEmployeeWorkshift()
    {
        $customTimeId       = Input::get('custom_time_id');
        $employeeId         = Input::get('employee_id');
        $date               = Input::get('date');
        try {
            $employee           = Employee::findOrFail($employeeId);
            $customTime         = (intval($customTimeId) !== 0)  ? CustomTime::find($customTimeId) : null;
            $employeeCustomTime = EmployeeCustomTime::getUpsertModel($employeeId, $date);
            if (!empty($customTime)) {
                $employeeCustomTime->fill([
                    'date' =>  $date
                ]);
                $employeeCustomTime->employee()->associate($employee);
                $employeeCustomTime->customTime()->associate($customTime);
                $employeeCustomTime->save();
            } else {
                //Delete existing row in db, otherwise do nothing
                if (!empty($employeeCustomTime->date)) {
                    $employeeCustomTime->delete();
                }
            }

            return Response::json(['success' => true]);
        } catch(\Exception $ex){
            return Response::json([
                'success' => false,
                'message' => $ex->getMessage()
            ]);
        }
    }

    /**
     * Upsert work-shifts for an employee in one month
     *
     *  @return Redirect
     */
    public function massUpdateWorkShift($employeeId)
    {
        $customTimes = Input::get('custom_times');
        $message = trans('as.crud.success_edit');

        try {
            $employee = Employee::ofCurrentUser()->find($employeeId);
            $current = Carbon::now();
            foreach ($customTimes as $date => $customTimeId) {
                $customTime = (intval($customTimeId) !== 0)  ? CustomTime::find($customTimeId) : null;
                $employeeCustomTime = EmployeeCustomTime::getUpsertModel($employeeId, $date);
                if (!empty($customTime)) {
                    $employeeCustomTime->fill([
                        'date' =>  $date
                    ]);
                    $employeeCustomTime->employee()->associate($employee);
                    $employeeCustomTime->customTime()->associate($customTime);
                    $employeeCustomTime->save();
                } else {
                    //Delete existing row in db, otherwise do nothing
                    if (!empty($employeeCustomTime->date)) {
                        $employeeCustomTime->delete();
                    }
                }
                $current = Carbon::createFromFormat('Y-m-d', $date);
            }

            // We need to rebuild the NAT calendar
            NAT::enqueueToRebuild($this->user);

            return Redirect::route(
                'as.employees.employeeCustomTime',
                ['employeeId' => $employeeId, 'date' => $current->format('Y-m')]
            )->with(
                'messages',
                $this->successMessageBag($message)
            );
        } catch (\Watson\Validating\ValidationException $ex) {
             return Redirect::back()->withInput()->withErrors($ex->getErrors());
        }
    }
}
