<?php namespace App\API\v1_0\Appointment\Controllers;

use Input, Response;
use App\Appointment\Models\Employee;
use App\Appointment\Models\ServiceCategory;

class Service extends Base
{
    public function getServices()
    {
        $employeeId = intval(Input::get('employee_id'));
        $employee = null;
        if ($employeeId > 0) {
            $employee = Employee::ofCurrentUser()->findOrFail($employeeId);
            $services = $employee->services()->where('is_active', '=', 1);
        } else {
            $services = \App\Appointment\Models\Service::ofCurrentUser();
        }

        $categoryId = intval(Input::get('category_id'));
        $category = null;
        if ($categoryId > 0) {
            $category = ServiceCategory::ofCurrentUser()->findOrFail($categoryId);
            $services->where('category_id', '=', $category->id);
        }

        $perPage = max(1, intval(Input::get('per_page', 15)));
        $services = $services->paginate($perPage);

        $servicesData = [];
        foreach ($services as $service) {
            $servicesData[] = $this->_prepareServiceData($service);
        }

        return Response::json([
            'error' => false,
            'data' => $servicesData,
            'pagination' => $this->_preparePagination($services),
        ]);
    }

    public function getService($id)
    {
        $service = \App\Appointment\Models\Service::ofCurrentUser()->findOrFail($id);

        return Response::json([
            'error' => false,
            'data' => $this->_prepareServiceData($service),
        ]);
    }

    public function getCategories()
    {
        $categories = ServiceCategory::ofCurrentUser();
        $categories->orderBy('order', 'asc');

        $perPage = max(1, intval(Input::get('per_page', 15)));
        $categories = $categories->paginate($perPage);

        $categoriesData = [];
        foreach ($categories as $category) {
            $categoriesData[] = $this->_prepareServiceCategoryData($category);
        }

        return Response::json([
            'error' => false,
            'data' => $categoriesData,
            'pagination' => $this->_preparePagination($categories),
        ]);
    }

    public function getCategory($id)
    {
        $category = ServiceCategory::ofCurrentUser()->findOrFail($id);

        return Response::json([
            'error' => false,
            'data' => $this->_prepareServiceCategoryData($category),
        ]);
    }
}
