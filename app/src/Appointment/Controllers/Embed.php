<?php namespace App\Appointment\Controllers;

use Hashids, Input, View, Session, Redirect, URL, Config, Validator, Event;
use App\Core\Models\User;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\EmployeeService;
use App\Consumers\Models\Consumer;
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
        $links  = [];
        foreach (Config::get('varaa.languages') as $lang) {
            $links[$lang] = URL::route('as.embed.embed', ['hash' => $this->user->hash], true, null, $lang);
        }
        return $this->render('index', [
            'links' => $links
        ]);
    }

    /**
     * Show the embed form to user
     *
     * @return View
     */
    public function preview()
    {
        return Redirect::route('as.embed.embed', ['hash' =>  $this->user->hash]);
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

        $serviceId       = Input::get('service_id');
        $serviceTimeId   = Input::get('service_time_id');
        $extraServiceIds = Input::get('extra_services');

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
        $extraServices = [];
        if(!empty($extraServiceIds)){
            $extraServices = ExtraService::whereIn('id', $extraServiceIds)->get();
        }
        $extraServiceLength = $extraServicePrice =  0;
        if(!empty($extraServices)){
            foreach ($extraServices as $extraService) {
                $extraServiceLength += $extraService->length;
                $extraServicePrice  += $extraService->price;
            }
        }

        $categories = ServiceCategory::OfUser($user->id)
            ->orderBy('order')
            ->with(['services' => function($query) {
                $query->where('is_active', true)
                    ->with('serviceTimes');
            }])->where('is_show_front', true)
            ->get();

        return $this->render('layout-'.$layoutId, [
            'user'               => $user,
            'categories'         => $categories,
            'employees'          => $employees,
            'booking_info'       => $booking_info,
            'consumer'           => $consumer,
            'service'            => $service,
            'serviceTime'        => $serviceTime,
            'extraServiceLength' => $extraServiceLength,
            'extraServicePrice'  => $extraServicePrice,
            'extraServices'      => $extraServices,
            'workingTimes'       => $workingTimes,
            'hash'               => $hash,
            'date'               => $date,
            'action'             => $action
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

        $layoutId = (int) Input::get('l');
        if (!$layoutId) {
            $layoutId = 1;
        }

        return $this->render('layout-'.$layoutId.'.extraServices', [
            'date'    => Input::get('date') ?: Carbon::now()->toDateString(),
            'service' => $service
        ]);
    }

    public function addConfirmInfo()
    {
        $data = Input::all();
        $hash = Input::get('hash');

        $decoded = Hashids::decrypt($hash);
        $user = User::find($decoded[0]);

        $fields = [
            trans('as.bookings.firstname') => Input::get('firstname'),
            trans('as.bookings.phone')     => Input::get('phone'),
            trans('as.bookings.email')     => Input::get('email'),
        ];

        $validators = [
            trans('as.bookings.firstname')  => array( 'required'),
            trans('as.bookings.phone')      => array( 'required'),
            trans('as.bookings.email')      => array( 'required', 'email'),
        ];

        $validation = Validator::make($fields, $validators);

        if ( $validation->fails() ) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        //TODO probably validate user info here
        Session::put('booking_info', $data);
        return Redirect::route('as.embed.embed', ['hash' => $hash, 'action'=> 'confirm']);
    }

}
