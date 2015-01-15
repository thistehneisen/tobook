<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Request, Input, Config, DB;
use Carbon\Carbon;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\ServiceExtraService;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Resource;
use App\Appointment\Models\Room;
use App\Appointment\Models\ResourceService;
use App\Appointment\Models\EmployeeService;
use App\Appointment\Models\Employee;

class Services extends AsBase
{
    use \CRUD;
    protected $viewPath = 'modules.as.services.service';
    protected $crudOptions = [
        'modelClass' => 'App\Appointment\Models\Service',
        'langPrefix' => 'as.services',
        'layout' => 'modules.as.layout',
        'showTab' => true,
        'indexFields' => [
            'name', 'employees', 'price', 'during', 'length', 'category', 'is_active'
        ],
        'presenters' => [
            'employees' => ['App\Appointment\Controllers\Services', 'presentEmployees'],
            'category' => ['App\Appointment\Controllers\Services', 'presentCategory'],
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function upsert($id = null)
    {
         $service = ($id !== null)
            ? Service::findOrFail($id)
            : new Service();
        $categories = ServiceCategory::ofCurrentUser()->lists('name','id');
        $resources  = Resource::ofCurrentUser()->lists('name', 'id');
        $rooms      = Room::ofCurrentUser()->lists('name', 'id');
        $extras     = ExtraService::ofCurrentUser()->lists('name', 'id');
        $employees  = Employee::ofCurrentUser()->get();

        return $this->render('form', [
            'service'    => $service,
            'categories' => $categories,
            'resources'  => $resources,
            'rooms'      => $rooms,
            'extras'     => $extras,
            'employees'  => $employees
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function upsertHandler($service)
    {
        try{
            $service->fill(Input::all());
            $service->setLength();
            // Attach user
            $service->user()->associate($this->user);
            $categoryId = (int) Input::get('category_id');
            if (!empty($categoryId)) {
                $category = ServiceCategory::find($categoryId);
                $service->category()->associate($category);
            }

            $service->saveOrFail();

            $extraServices = Input::get('extras', []);
            $resources     = Input::get('resources', []);
            $rooms         = Input::get('rooms', []);
            $employees     = Input::get('employees', []);
            $plustimes     = Input::get('plustimes');

            // detact all existing employees before attaching new one
            $existingEmployeeIds = [];
            foreach ($service->employees as $serviceEmployee) {
                $existingEmployeeIds[] = $serviceEmployee->id;
            }
            $service->employees()->detach($existingEmployeeIds);

            foreach ($employees as $employeeId) {
                $employee = Employee::ofCurrentUser()->find($employeeId);
                $employeeService = new EmployeeService();
                $employeeService->service()->associate($service);
                $employeeService->employee()->associate($employee);
                $employeeService->plustime = $plustimes[$employeeId];
                $employeeService->save();
            }

            //Delete old extra service services;
            DB::table('as_extra_service_service')
                ->where('service_id', $service->id)
                ->delete();

            foreach ($extraServices as $extraServiceId) {
                $extraService = ExtraService::find($extraServiceId);
                $serviceExtraService = new ServiceExtraService;
                $serviceExtraService->service()->associate($service);
                $serviceExtraService->extraService()->associate($extraService);
                $serviceExtraService->save();
            }

            //Delete old resource services;
            DB::table('as_resource_service')
                ->where('service_id', $service->id)
                ->delete();

            foreach ($resources as $resourceId) {
                $resource = Resource::find($resourceId);
                $resourceService = new ResourceService;
                $resourceService->service()->associate($service);
                $resourceService->resource()->associate($resource);
                $resourceService->save();
            }
            Room::associateWithService($rooms, $service);

        } catch(\Watson\Validating\ValidationException $ex) {
           throw $ex;
        }
        return $service;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {

        $service = Service::ofCurrentUser()->findOrFail($id);

        //Cannot delete this service if is there any undeleted booking use it
        if (!$service->isDeletable()) {
            //if there are bookings, redirect back
            $errors = $this->errorMessageBag(trans('as.services.error.service_current_in_use'));
            return Redirect::route(static::$crudRoutes['index'])
                ->withInput()->withErrors($errors, 'top');
        }

        $service->delete();

        if (Request::ajax() === true) {
            return Response::json(['success' => true]);
        }

        return Redirect::route(static::$crudRoutes['index'])
            ->with(
                'messages',
                $this->successMessageBag(trans('as.crud.success_delete'))
            );
    }

    /**
     * Display service time of a service
     * Handle insert service time for service
     */
    public function customTime($serviceId)
    {
        $perPage      = (int) Input::get('perPage', Config::get('view.perPage'));
        $fields       = with(new ServiceTime)->fillable;
        $service      = Service::ofCurrentUser()->find($serviceId);
        $serviceTimes = ServiceTime::where('service_id', $serviceId)
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        return View::make('modules.as.services.customTime.index', [
            'items'      => $serviceTimes,
            'service'    => $service,
            'fields'     => $fields,
            'langPrefix' => $this->langPrefix,
            'now'        => Carbon::now()
        ]);
    }

    /**
     * Show the form to edit service time
     *
     * @param int $id
     * @param int $customTimeId
     *
     * @return View
     */
    public function upsertCustomTime($id, $customTimeId)
    {
        $service = Service::ofCurrentUser()->findOrFail($id);
        $serviceTime = ServiceTime::where('service_id', $id)
            ->where('id', $customTimeId)
            ->firstOrFail();

        return View::make('modules.as.services.customTime.upsert', [
            'service'    => $service,
            'serviceTime'=> $serviceTime,
            'now'        => Carbon::now()
        ]);
    }

    /**
     * Add/edit service time of an service
     *
     * @param int $id ServiceId
     *
     * @return Redirect
     */
    public function doUpsertCustomTime($id, $serviceTimeId = null)
    {
        try {
            $message = ($serviceTimeId !== null)
            ? trans('as.crud.success_edit')
            : trans('as.crud.success_add');

            $service = Service::ofCurrentUser()->findOrFail($id);

            $serviceTime = ($serviceTimeId === null)
                ? new ServiceTime
                : ServiceTime::where('service_id', $id)
                ->where('id', $serviceTimeId)
                ->firstOrFail();

            $serviceTime->fill([
                'price'       => Input::get('price'),
                'before'      => Input::get('before'),
                'during'      => Input::get('during'),
                'after'       => Input::get('after'),
                'description' => Input::get('description'),
            ]);

            $serviceTime->setLength();

            $serviceTime->service()->associate($service);
            $serviceTime->save();

            return Redirect::route('as.services.customTime', ['id' => $id])
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
        $customTime = ServiceTime::where('service_id', $id)
            ->where('id', $customTimeId)
            ->delete();

        return Redirect::route('as.services.customTime', ['id' => $id])
            ->with(
                'messages',
                $this->successMessageBag(trans('as.crud.success_delete'))
            );
    }

    public static function presentEmployees($value, $item)
    {
        $str = '';
        foreach ($item->employees as $e) {
            $str .= $e->name . ', ';
        }

        return $str;
    }

    public static function presentCategory($value, $item)
    {
        return $item->category->name;
    }
}
