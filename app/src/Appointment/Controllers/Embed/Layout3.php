<?php namespace App\Appointment\Controllers\Embed;

use Input, Response;
use App\Appointment\Controllers\Embed;
use App\Appointment\Models\Service;

class Layout3 extends Embed
{
    /**
     * Get all employees available for a service
     *
     * @return View
     */
    public function getEmployees()
    {
        $serviceId = Input::get('serviceId');
        if ($serviceId === null) {
            return Response::json(['message' => 'Missing service ID'], 400);
        }

        $service = Service::with('employees')->findOrFail($serviceId);
        return $this->render($this->getLayout().'.employees', [
            'employees' => $service->employees
        ]);
    }
}
