<?php namespace App\Appointment\Controllers\Embed;

use Hashids, Input, Session, Redirect, URL, Config, Validator, App, Cart, Response;
use App\Appointment\Models\Service;
use App\Core\Models\User;
use App\Appointment\Controllers\AsBase;

class Base extends AsBase
{
    use Layout;

    protected $viewPath = 'modules.as.embed';

    /**
     * @{@inheritdoc}
     */
    protected function render($tpl, $data = [])
    {
        // No need to getLayout() everytime
        return parent::render($this->getLayout().'.'.$tpl, $data);
    }

    /**
     * Display the booking form of provided user
     *
     * @param string $hash UserID hashed
     *
     * @return View
     */
    public function index($hash)
    {
        $data = $this->handleIndex($hash);

        return empty($data)
            ? Redirect::route('as.embed.embed', ['hash' => $hash])
            : $this->render('index', $data);
    }

    /**
     * Get all employees available for a service
     *
     * @return View
     */
    public function getEmployees()
    {
        $hash = Input::get('hash');
        $serviceId = Input::get('serviceId');
        if ($serviceId === null) {
            return Response::json(['message' => 'Missing service ID'], 400);
        }

        $service = Service::with('employees')->findOrFail($serviceId);
        $employees = $this->getEmployeesOfService($service);

        $user = empty($user)
            ? $this->getUser($hash)
            : $user;

        return $this->render('employees', [
            'employees' => $employees,
            'service'   => $service,
            'user'      => $user
        ]);
    }

    /**
     * The all active employees of a service
     *
     * @param Service $service
     *
     * @return Illuminate\Support\Collection
     */
    protected function getEmployeesOfService(Service $service)
    {
        return $service->employees()->where('is_active', true)->orderBy('order')->get();
    }

    /**
     * Get booking info from Session
     *
     * @return array
     */
    protected function getBookingInfo()
    {
        if (!empty(Session::get('booking_info'))) {
            return Session::get('booking_info');
        }
    }
}
