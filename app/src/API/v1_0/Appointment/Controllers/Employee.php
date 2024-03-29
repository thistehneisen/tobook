<?php namespace App\API\v1_0\Appointment\Controllers;

use App\Appointment\Models\Service;
use Input;
use Response;

class Employee extends Base
{
    /**
     * Display employees of current business owner.
     *
     * @return Response
     */
    public function index()
    {
        $serviceId = intval(Input::get('service_id'));
        $service = null;
        if ($serviceId > 0) {
            $service = Service::ofCurrentUser()->findOrFail($serviceId);
            $employees = $service->employees()->where('is_active', '=', 1);
        } else {
            $employees = \App\Appointment\Models\Employee::ofCurrentUser()->where('is_active', '=', 1);
        }

        $perPage = intval(Input::get('per_page', 15));
        if ($perPage > 0) {
            $employees = $employees->paginate($perPage);
        } else {
            $employees = $employees->get();
        }

        $employeesData = [];
        foreach ($employees as $employee) {
            $employeesData[] = $this->_prepareEmployeeData($employee);
        }

        return Response::json([
            'error' => false,
            'data' => $employeesData,
            'pagination' => $this->_preparePagination($employees),
        ]);
    }

    /**
     * Store a newly created employee.
     *
     * @return Response
     */
    public function store()
    {
        $employee = new \App\Appointment\Models\Employee(Input::all());
        $employee->user()->associate($this->user);
        $employee->save();

        if ($employee->id) {
            return Response::json([
                'error' => false,
                'data' => $this->_prepareEmployeeData($employee),
            ], 200);
        } else {
            return Response::json([
                'error' => true,
                'message' => $employee->getErrors()->first(),
            ], 400);
        }
    }


    /**
     * Display the specified employee.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $employee = \App\Appointment\Models\Employee::ofCurrentUser()->findOrFail($id);

        return Response::json([
            'error' => false,
            'data' => $this->_prepareEmployeeData($employee),
        ], 200);
    }


    /**
     * Update the specified employee.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $employee = \App\Appointment\Models\Employee::ofCurrentUser()->findOrFail($id);

        $employee->fill(Input::all());

        if ($employee->save()) {
            return Response::json([
                'error' => false,
                'data' => $this->_prepareEmployeeData($employee),
            ], 200);
        } else {
            return Response::json([
                'error' => true,
                'message' => $employee->getErrors()->first(),
            ], 400);
        }
    }

    /**
     * Delete the specified employee.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $employee = \App\Appointment\Models\Employee::ofCurrentUser()->findOrFail($id);

        $employee->delete();

        return Response::json([
            'error' => false,
            'message' => 'Employee has been deleted.',
        ], 200);
    }
}
