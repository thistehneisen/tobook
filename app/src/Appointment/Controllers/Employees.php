<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Util, Response, Validator;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeDefaultTime;
use App\Appointment\Models\EmployeeFreetime;
use App\Appointment\Models\EmployeeCustomTime;
use App\Appointment\Models\Service;
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
            ? Employee::findOrFail($id)
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
        $employee = Employee::find($id);
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
        $employee = Employee::find($employeeId);

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
        $employeeId = Input::get('employee_id');
        $bookingDate = Input::get('booking_date');
        $startTime = Input::get('start_time');
        $endTime = with(new Carbon($startTime))->addMinutes(60);
        $employee  = Employee::find($employeeId);
        $employees = Employee::ofCurrentUser()->lists('name','id');

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
            $employee = Employee::find($employeeId);
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
    public function customTime($id)
    {
        $employee    = Employee::findOrFail($id);
        $fields      = with(new EmployeeCustomTime)->fillable;
        $perPage     = (int) Input::get('perPage', Config::get('view.perPage'));
        $customTimes = $employee
            ->customTimes()
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        return $this->render('customTime.index', [
            'employee'   => $employee,
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
    public function upsertCustomTime($id, $customTimeId)
    {
        $employee = Employee::findOrFail($id);
        $customTime = EmployeeCustomTime::where('employee_id', $id)
            ->where('id', $customTimeId)
            ->firstOrFail();

        return $this->render('customTime.upsert', [
            'employee'   => $employee,
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
    public function doUpsertCustomTime($id, $customTimeId = null)
    {
        try {
            $employee = Employee::findOrFail($id);

            $customTime = ($customTimeId !== null)
                ? EmployeeCustomTime::findOrFail($customTimeId)
                : new EmployeeCustomTime;

            $customTime->fill([
                'date'       => Input::get('date'),
                'start_at'   => Input::get('start_at'),
                'end_at'     => Input::get('end_at'),
                'is_day_off' => Input::get('is_day_off', false),
            ]);
            $customTime->employee()->associate($employee);
            $customTime->save();
        } catch (\Watson\Validating\ValidationException $ex) {
            return Redirect::back()->withInput()->withErrors($ex->getErrors());
        }

        $message = ($customTimeId !== null)
            ? trans('as.crud.success_edit')
            : trans('as.crud.success_add');

        return Redirect::route('as.employees.customTime', ['id' => $id])
            ->with(
                'messages',
                $this->successMessageBag($message)
            );
    }
}
