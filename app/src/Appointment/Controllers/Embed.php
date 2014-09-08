<?php namespace App\Appointment\Controllers;

use Hashids, Input, View, Session, Redirect;
use App\Core\Models\User;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\ServiceCategory;
use App\Core\Models\Consumer;
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

        $serviceId      = Input::get('service_id');
        $serviceTimeId  = Input::get('service_time_id');
        $date = (empty(Input::get('date'))) ? Carbon::today() : Input::get('date');
        $consumer = null;
        $booking_info = [];
        $action = Input::get('action');

        //If carts is empty, user cannot checkout
        if($action === 'checkout'){
            if(empty(Session::get('carts'))){
                return Redirect::route('as.embed.embed', ['hash' => $hash]);
            }
        } else if($action === 'confirm'){
            if(empty(Session::get('booking_info'))){
                return Redirect::route('as.embed.embed', ['hash' => $hash]);
            }
        }
        if(!empty(Session::get('booking_info'))){
            $booking_info = Session::get('booking_info');
            $consumer = Consumer::where('email', $booking_info['email'])->first();
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
        if(!empty($serviceId) && !empty($date)){
            $service = Service::find($serviceId);
            if(!empty($serviceTimeId)){
                $serviceTime = Servicetime::find($serviceTimeId);
            }
            $employees = $service->employees;
        }

        $categories = ServiceCategory::OfUser($user->id)
            ->with(['services' => function($query){
                 $query->where('is_active', true);
            }])->where('is_show_front', true)
            ->get();

        return $this->render('layout-'.$layoutId, [
            'categories'    => $categories,
            'employees'     => $employees,
            'booking_info'  => $booking_info,
            'consumer'      => $consumer,
            'service'       => $service,
            'serviceTime'   => $serviceTime,
            'workingTimes'  => $workingTimes,
            'hash'          => $hash,
            'date'          => $date,
            'action'        => $action
        ]);
    }

    /**
     * Display the adding extra service form
     *
     * @return View
     */
    public function getExtraServiceForm()
    {
        $service = Service::findOrFail(Input::get('service_id'));

        return $this->render('extraServices', [
            'date'    => Input::get('date') ?: Carbon::now()->toDateString(),
            'service' => $service
        ]);
    }

    public function addConfirmInfo()
    {
        $data = Input::all();
        $hash = Input::get('hash');
        //TODO probably validate user info here
        Session::put('booking_info', $data);
        return Redirect::route('as.embed.embed', ['hash' => $hash, 'action'=> 'confirm']);
    }

}
