<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Util, Response, Validator;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeDefaultTime;
use App\Appointment\Models\EmployeeFreetime;
use App\Appointment\Models\EmployeeCustomTime;
use App\Appointment\Models\Service;
use App\Appointment\Models\CustomTime;
use Carbon\Carbon;

class Employees extends AsBase
{
    use App\Appointment\Traits\Crud;

    protected $viewPath = 'modules.as.employees';
    protected $langPrefix = 'as.employees';
    protected $crudIndexFields = ['avatar', 'name', 'email', 'phone', 'is_active'];
    protected $crudSortable = true;

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
            'employeeId'=> $employee->id
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function upsertHandler($item)
    {
        // Fill general information
        $item->fill([
            'name'                => Input::get('name'),
            'email'               => Input::get('email'),
            'phone'               => Input::get('phone'),
            'description'         => Input::get('description'),
            'is_subscribed_email' => Input::get('is_subscribed_email', false),
            'is_subscribed_sms'   => Input::get('is_subscribed_sms', false),
            'is_active'           => Input::get('is_active')
        ]);

        // Update data
        if (Input::hasFile('avatar')) {
            $file            = Input::file('avatar');
            $destinationPath = public_path(Config::get('varaa.upload_folder')).'/avatars';
            $filename        = str_random(6) . '_' . $file->getClientOriginalName();
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
        $workingTimes = range(8,17);
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
            $employeeFreetime = new EmployeeFreetime();
            $employeeFreetime->fill(Input::all());
            $employee = Employee::ofCurrentUser()->find($employeeId);
            $employeeFreetime->user()->associate($this->user);
            $employeeFreetime->employee()->associate($employee);
            $employeeFreetime->save();
            $data['success'] = true;
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
            $freetime = EmployeeFreetime::find($freetimeId);
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
        $fields      = with(new CustomTime)->fillable;
        $perPage     = (int) Input::get('perPage', Config::get('view.perPage'));
        $customTimes = CustomTime::ofCurrentUser()
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        return $this->render('customTime.index', [
            'items'      => $customTimes,
            'fields'     => $fields,
            'langPrefix' => $this->langPrefix,
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

    public function deleteCustomTime($id, $customTimeId)
    {
        $customTime = CustomTime::ofCurrentUser()->find($customTimeId)->delete();

        return Redirect::route('as.employees.customTime')
            ->with(
                'messages',
                $this->successMessageBag(trans('as.crud.success_delete'))
            );
    }

    public function employeeCustomTime($employeeId = null, $date = null)
    {
        $customTimes = CustomTime::ofCurrentUser()
            ->orderBy('id', 'desc')->lists('name','id');

        $employee =  (!empty($employeeId))
            ? Employee::ofCurrentUser()->find($employeeId)
            : Employee::ofCurrentUser()->first();

        $employees = Employee::ofCurrentUser()->get();

        $current = Carbon::now();
        if(!empty($date)){
            try{
                $current = Carbon::createFromFormat('Y-m-d', $date . '-01');
            } catch(\Exception $ex){
                $current = Carbon::now();
            }
        }
        $startOfMonth = $current->startOfMonth()->toDateString();
        $endOfMonth   = $current->endOfMonth()->toDateString();

        $items = $employee->employeeCustomTimes()
            ->where('date','>=', $startOfMonth)
            ->where('date','<=', $endOfMonth)->get();

        return $this->render('customTime', [
            'customTimes'  => $customTimes,
            'items'        => $items,//custom time of the employee
            'employee'     => $employee,
            'employees'    => $employees,
            'current'      => $current,
            'startOfMonth' => $startOfMonth,
            'endOfMonth'   => $endOfMonth
        ]);
    }

    public function upsertEmployeeCustomTime()
    {
        $customTimeId = Input::get('custom_times');
        $fromDateStr  = Input::get('from_date');
        $toDateStr    = Input::get('to_date');
        $employeeId   = Input::get('employees');
        $message      = trans('as.crud.success_add');

        try{
            $employee = Employee::ofCurrentUser()->findOrFail($employeeId);
            $customTime = CustomTime::ofCurrentUser()->findOrFail($customTimeId);

            $fromDate     = new Carbon($fromDateStr);
            $toDate       = (!empty($toDateStr)) ? (new Carbon($toDateStr)) : $fromDate;

            if($fromDate > $toDate) {
                return Redirect::back()->withInput()->withErrors(trans('as.employees.error.invalid_date_range'));
            }

            $dateRange = ($fromDate->diffInDays($toDate))
                        ? $fromDate->diffInDays($toDate) + 1
                        : 0;

            if($dateRange){
                while($dateRange){
                    $employeeCustomTime =  EmployeeCustomTime::getUpsertModel($employeeId, $fromDate->toDateString());
                    $employeeCustomTime->fill([
                        'date' =>  $fromDate->toDateString()
                    ]);
                    $employeeCustomTime->employee()->associate($employee);
                    $employeeCustomTime->customTime()->associate($customTime);
                    $employeeCustomTime->save();
                    $fromDate->addDay();
                    $dateRange--;
                }
            } else {
                $employeeCustomTime = EmployeeCustomTime::getUpsertModel($employeeId, $fromDate->toDateString());
                $employeeCustomTime->fill([
                    'date' =>  $fromDate->toDateString()
                ]);
                $employeeCustomTime->employee()->associate($employee);
                $employeeCustomTime->customTime()->associate($customTime);
                $employeeCustomTime->save();
            }

           return Redirect::route('as.employees.employeeCustomTime', ['employeeId' => $employeeId])
                ->with(
                    'messages',
                    $this->successMessageBag($message)
                );

        } catch (\Watson\Validating\ValidationException $ex){
             return Redirect::back()->withInput()->withErrors($ex->getErrors());
        }

    }

    public function deleteEmployeeCustomTime($empCustomTimeId, $employeeId, $date = null)
    {
        $customTime = EmployeeCustomTime::find($empCustomTimeId)->delete();

        return Redirect::route('as.employees.employeeCustomTime', ['employeeId' => $employeeId, 'date' => $date])
            ->with(
                'messages',
                $this->successMessageBag(trans('as.crud.success_delete'))
            );
    }

    public function massiveUpdateEmployeeCustomTime($employeeId)
    {
        $customTimes = Input::get('custom_times');
        $message      = trans('as.crud.success_edit');
        try{

            $employee = Employee::ofCurrentUser()->findOrFail($employeeId);
            $current = Carbon::now();
            foreach ($customTimes as $date => $customTimeId) {
                $customTime = CustomTime::find($customTimeId);
                $employeeCustomTime = EmployeeCustomTime::getUpsertModel($employeeId, $date);
                $employeeCustomTime->fill([
                    'date' =>  $date
                ]);
                $employeeCustomTime->employee()->associate($employee);
                $employeeCustomTime->customTime()->associate($customTime);
                $employeeCustomTime->save();
                $current = Carbon::createFromFormat('Y-m-d', $date);
            }
            return Redirect::route('as.employees.employeeCustomTime', ['employeeId' => $employeeId, 'date' => $current->format('Y-m')])
                ->with(
                    'messages',
                    $this->successMessageBag($message)
                );
        } catch (\Watson\Validating\ValidationException $ex){
             return Redirect::back()->withInput()->withErrors($ex->getErrors());
        }
    }
}
