<?php namespace App\Appointment\Controllers;

use Hashids, Input, View;
use App\Core\Models\User;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\Service;
use Carbon\Carbon;

class Embed extends AsBase
{
    protected $viewPath = 'modules.as.embed';

    /**
     * Show embeded link for user to install on their website
     *
     * @return View
     */
    public function index()
    {
        return $this->render('index', [
            'link' => route('as.embed.embed', ['hash' => $this->user->hash])
        ]);
    }

    /**
     * Show the embed form to user
     *
     * @return View
     */
    public function preview()
    {
        return $this->render('preview', [
            'link' => route('as.embed.embed', ['hash' => $this->user->hash])
        ]);
    }

    /**
     * Display the booking form of provided user
     *
     * @param string $hash UserID hashed
     *
     * @return View
     */
    public function embed($hash)
    {
        $decoded = Hashids::decrypt($hash);
        $user = User::find($decoded[0]);

        $layoutId = (int) Input::get('l');
        if (!$layoutId) {
            $layoutId = 1;
        }

        $serviceId = Input::get('service_id');
        $date = (empty(Input::get('date'))) ? Carbon::today() : Input::get('date');

        if (!$date instanceof Carbon) {
            try {
                $date = new Carbon($date);
            } catch (\Exception $ex) {
                $date = Carbon::today();
            }
        }

        $employees= [];
        $service  = null;
        $workingTimes = range(8,17);
        //for select employee view
        if(!empty($serviceId) && !empty($date)){
            $service = Service::find($serviceId);
            $employees = $service->employees;
        }

        $categories = ServiceCategory::OfUser($user->id)
            ->with('services')
            ->where('is_show_front', true)
            ->get();

        return $this->render('layout-'.$layoutId, [
            'categories'    => $categories,
            'employees'     => $employees,
            'service'       => $service,
            'workingTimes'  => $workingTimes,
            'hash'          => $hash,
            'date'          => $date
        ]);
    }


    /**
     * Display the adding extra service form
     *
     * @return View
     */
    public function getExtraServiceForm(){
        $serviceId = (int) Input::get('service_id');
        $service = Service::find($serviceId);
        $extraServices = $service->extraServices;
        return View::make('modules.as.embed.extraServices', [
            'extraServices' => $extraServices
        ]);
    }

}
