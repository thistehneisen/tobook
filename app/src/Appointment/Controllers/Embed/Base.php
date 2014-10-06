<?php namespace App\Appointment\Controllers\Embed;

use Hashids, Input, Session, Redirect, URL, Config, Validator, App;
use Carbon\Carbon;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\EmployeeService;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\AsConsumer;
use App\Consumers\Models\Consumer;
use App\Core\Models\Cart;
use App\Core\Models\CartDetail;
use App\Core\Models\User;
use App\Appointment\Controllers\AsBase;

class Base extends AsBase
{
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
        $user = $this->getUser($hash);

        $serviceId       = Input::get('service_id');
        $serviceTimeId   = Input::get('service_time');
        $extraServiceIds = Input::get('extra_services');
        $cartId          = Input::get('cart_id');

        $date = (empty(Input::get('date'))) ? Carbon::today() : Input::get('date');
        $consumer = null;
        $cart = null;
        $action = Input::get('action');

        //If carts is empty, user cannot checkout
        if($action === 'checkout'){
            if(empty($cartId)){
                return Redirect::route('as.embed.embed', ['hash' => $hash]);
            }
        } else if($action === 'confirm'){
            if(empty($cartId)){
                return Redirect::route('as.embed.embed', ['hash' => $hash]);
            }
        }
        if(!empty($cartId)){
            $cart = Cart::find($cartId);
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

            $serviceTime = (!empty($serviceTimeId))
                ? ServiceTime::find($serviceTimeId)
                : null;

            $employees = $service->employees()->where('is_active', true)->get();
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

        $layout = $this->getLayout();
        return $this->render('index', [
            'layout'             => $layout,
            'user'               => $user,
            'categories'         => $categories,
            'employees'          => $employees,
            'cart'               => $cart,
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
     * Get layout for this embed
     *
     * @return int
     */
    protected function getLayout()
    {
        $layoutId = (int) Input::get('l');
        if (!$layoutId) {
            $layoutId = 1;
        }

        return 'layout-'.$layoutId;
    }

    /**
     * Get the User object associated to this embed
     *
     * @param string $hash
     *
     * @return App\Core\Models\User
     */
    protected function getUser($hash = null)
    {
        $hash = $hash ?: Input::get('hash');
        if ($hash === null) {
            return App::abort(404);
        }

        $decoded = Hashids::decrypt($hash);
        if (empty($decoded[0])) {
            return App::abort(404);
        }

        return User::findOrFail($decoded[0]);
    }

    protected function getConfirmationValidator()
    {
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

        return Validator::make($fields, $validators);
    }
}
