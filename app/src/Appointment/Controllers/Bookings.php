<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response, DB, Cart;
use Util, Hashids, Session, Request, Mail, Sms;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Core\Models\User;
use App\Core\Models\CartDetail;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeService;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\Resource;
use App\Appointment\Models\AsConsumer;
use App\Appointment\Models\Consumer;
use App\Appointment\Models\Observer\EmailObserver;
use App\Appointment\Models\Observer\SmsObserver;

class Bookings extends AsBase
{
    use App\Appointment\Traits\Crud;
    protected $viewPath   = 'modules.as.bookings';
    protected $langPrefix = 'as.bookings';
    protected $crudShowTab = false;

    /**
     * {@inheritdoc}
     */
    public function index()
    {
        $query = $this->getModel()
                ->ofCurrentUser()
                ->where('status','!=', Booking::STATUS_CANCELLED)
                ->orderBy('date','desc');

        // If this controller is sortable
        if (isset($this->crudSortable) && $this->crudSortable === true) {
            $query = $query->orderBy('order');
        }

        // Pagination please
        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $items = $query->paginate($perPage);

        return $this->renderList($items);
    }

    /**
     * {@inheritdoc}
     */
    public function upsert($id = null)
    {
        $model = $this->getModel();
        $item = ($id !== null)
            ? $model->ofCurrentUser()->findOrFail($id)
            : new $model();

        $view = $this->getViewPath().'.upsert.form';
        $data = $this->getBookingData($id);

        return View::make($view, array_merge($data, [
                'upsert' => true
            ])
        );
    }

    /**
     * {@inheritdoc}
     */
    public function applyExtraSearch($query, $q)
    {
        $fillable = with(new Consumer)->fillable;
        $table    = with(new Consumer)->getTable();
        $query->join('as_consumers', 'as_consumers.consumer_id', '=', 'as_bookings.consumer_id')
            ->join('consumers', 'consumers.id', '=','as_consumers.consumer_id')
            ->orWhere(function($subQuery) use ($fillable, $q, $table){
                foreach ($fillable as $field) {
                    $subQuery = $subQuery->orWhere($table  . '.' . $field, 'LIKE', '%'.$q.'%');
                }
                return $subQuery;
            })->select(
                'as_bookings.id',
                'as_bookings.uuid',
                'as_bookings.date',
                'as_bookings.total',
                'as_bookings.notes',
                'as_bookings.status',
                'consumers.id as consumer_id'
            )->groupBy('as_bookings.id');

        return $query;
    }

    /**
     * Confirm cancel booking by uuid
     *
     * @return View
     */
    public function cancel($uuid)
    {
        $data = [];
        try {
            $booking = Booking::where('uuid', $uuid)
                ->where('status','!=', Booking::STATUS_CANCELLED)
                ->firstOrFail();

            $data['confirm'] = sprintf(trans('as.bookings.cancel_confirm'), $uuid);
            $data['uuid']    = $uuid;
        } catch (\Exception $ex) {
            $data['error'] = trans('as.bookings.error.uuid_notfound');
        }

        return View::make('modules.as.bookings.cancel', $data);
    }

     /**
     *  Cancel booking by uuid
     *
     * @return View
     */
    public function doCancel($uuid)
    {
        try {
            $booking = Booking::where('uuid', $uuid)->first();
            $booking->status = Booking::STATUS_CANCELLED;
            $booking->delete_reason = 'Cancelled by UUID';
            $booking->save();
            $booking->delete();

            $msg = str_replace('{BookingID}', $uuid, trans('as.bookings.cancel_message'));
            $msg = str_replace('{Services}', $booking->getServiceInfo(), $msg);
            $data['message'] =  $msg;
        } catch (\Exception $ex) {
            $data['error'] = trans('as.bookings.error.uuid_notfound');
        }

        return View::make('modules.as.bookings.cancel', $data);
    }

    public function getBookingData($bookingId)
    {
        $booking              = Booking::ofCurrentUser()->find($bookingId);
        $bookingStatuses      = Booking::getStatuses();
        $employee             = $booking->employee;
        $services             = $employee->services;
        $bookingService       = $booking->bookingServices()->first();
        $bookingExtraServices = $booking->extraServices()->get();

        $categories = $this->getCategories($services);

        $bookingCategoryId   = (!empty($bookingService->service->category->id)) ? $bookingService->service->category->id : null;
        $bookingServiceId    = (!empty($bookingService->service->id)) ? $bookingService->service->id : null;
        $bookingServices     = $employee->services()->where('category_id', $bookingCategoryId)->lists('name','id');
        $bookingServices[-1] = trans('common.select');
        ksort($bookingServices);//sort selected services by key

        $serviceTimes = (!empty($bookingService)) ? $bookingService->service->serviceTimes : [];
        $length = (!empty($bookingService)) ? $bookingService->service->length : 0;
        $description = (!empty($bookingService)) ? $bookingService->service->description : '';

        $serviceTimesList = $bookingService->service->getServiceTimesData();

        $bookingServiceTime = (!empty($booking->bookingServices()->first()->serviceTime->id))
                            ? $booking->bookingServices()->first()->serviceTime->id
                            : 'default';

        $startAt = with(new Carbon($booking->start_at))->format('H:i');
        $endAt   = with(new Carbon($booking->end_at))->format('H:i');

        return [
            'booking'               => $booking,
            'uuid'                  => $booking->uuid,
            'modifyTime'            => $booking->modify_time,
            'bookingDate'           => $booking->date,
            'startTime'             => $startAt,
            'endTime'               => $endAt,
            'bookingStatuses'       => $bookingStatuses,
            'bookingService'        => $bookingService,
            'bookingServiceTime'    => $bookingServiceTime,
            'bookingExtraServices'  => $bookingExtraServices,
            'employee'              => $employee,
            'category_id'           => $bookingCategoryId,
            'service_id'            => $bookingServiceId,
            'categories'            => $categories,
            'services'              => $bookingServices,
            'serviceTimes'          => $serviceTimesList,
        ];
    }

    /**
     * Handle ajax request to display booking form
     *
     * @return View
     **/
    public function getBookingForm()
    {
        $bookingId   = (int) Input::get('booking_id', 0);

        if(empty($bookingId)){
            return $this->getBlankBookingForm();
        }

        $data = $this->getBookingData($bookingId);

        return View::make('modules.as.bookings.form', $data);
    }

    private function getBlankBookingForm()
    {
        $employeeId  = (int) Input::get('employee_id');
        $bookingDate = Input::get('booking_date');
        $startTime   = Input::get('start_time');

        $bookingStatuses = Booking::getStatuses();

        $employee = Employee::ofCurrentUser()->find($employeeId);
        $services = $employee->services;

        $categories = $this->getCategories($services);

        return View::make('modules.as.bookings.form', [
            'uuid'            => Util::uuid(),
            'employee'        => $employee,
            'categories'      => $categories,
            'bookingDate'     => $bookingDate,
            'startTime'       => $startTime,
            'bookingStatuses' => $bookingStatuses
        ]);
    }

    private function getCategories($services)
    {
        $categories[-1]   = trans('common.select');

        foreach ($services as $service) {
            //for getting distinct categories
            if(empty($service->category->id)) {
                continue;
            }
            $categories[$service->category->id] = $service->category->name;
        }
        return $categories;
    }

    public function getModifyBookingForm()
    {
        $bookingId = Input::get('booking_id');
        $booking = Booking::ofCurrentUser()->find($bookingId);
        $bookingStatuses = Booking::getStatuses();

        $bookingExtraServices = $booking->extraServices()->lists('extra_service_id');

        $extraServices = $booking->bookingServices()
                            ->first()->service
                            ->extraServices();
        if(!empty($bookingExtraServices)){
            $data = $extraServices->whereNotIn('as_extra_services.id', $bookingExtraServices);
        }

        $extras = $extraServices->lists('name', 'id');
        $resources = $booking->getBookingResources();

        return View::make('modules.as.bookings.modifyForm', [
            'booking'         => $booking,
            'extraServices'   => $extras,
            'resources'       => $resources,
            'bookingStatuses' => $bookingStatuses,
            'modifyTime'      => $booking->modify_time
        ]);
    }

    public function doModifyBooking()
    {
        $resultStatus = $this->changeStatus();
        $isSuccessful = false;
        if (Input::get('booking_status') === 'cancelled' && $resultStatus['success']) {
            $isSuccessful = true;
        } else {
            $resultExtraServices = $this->handleExtraModifiedTimes();
            if ($resultStatus['success'] && $resultExtraServices['success']) {
                $isSuccessful = true;
            }
        }

        if ($isSuccessful) {
            return Response::json([
                'success'=> true,
                'message'=> trans('as.bookings.modify_booking_successful'),
            ]);
        } else {
            // TODO: this error message is for debugging since it can be various cases
            return Response::json([
                'success'=> false,
                'message'=> $resultStatus['message'],
            ]);
        }
    }

    public function handleExtraModifiedTimes()
    {
        $extraServiceIds = Input::get('extra_services', []);
        $bookingId       = Input::get('booking_id');
        $modifyTime      = Input::get('modify_times');
        $booking         = Booking::ofCurrentUser()->find($bookingId);

        $bookingService = BookingService::where('booking_id', $bookingId)->first();
        $employee = $bookingService->employee;
        $service  = $bookingService->service;

        $extraServiceTime  = 0;
        $extraServicePrice = 0;
        $extraServices = [];

        foreach ($extraServiceIds as $id) {
            $extraService = ExtraService::find($id);
            $extraServiceTime  += $extraService->length;
            $extraServicePrice += $extraService->price;
            $extraServices[] = $extraService;
        }

        $length = (!empty($bookingService->serviceTime->length))
                    ? $bookingService->serviceTime->length
                    : $bookingService->service->length;

        $plustime = $employee->getPlustime($service->id);

        $total = $length + $plustime + $extraServiceTime + $modifyTime;
        if ($total < 1) {
            return [
                'success' => false,
                'message' => trans('as.bookings.error.empty_total_time')
            ];
        }

        $date        = new Carbon($booking->date);
        $startTime   = Carbon::createFromFormat('Y-m-d H:i:s', sprintf('%s %s', $booking->date, $booking->start_at));
        $endTime     = Carbon::createFromFormat('Y-m-d H:i:s', sprintf('%s %s', $booking->date, $booking->end_at));
        $newEndTime  = with(clone $startTime)->addMinutes($total);

        $isBookable = Booking::isBookable($booking->employee->id, $date, $endTime, $newEndTime, $booking->uuid);

        $data['success'] = true;
        $data['message'] = '';
        if ($isBookable) {
            try {
                $booking->total              = $total;
                $booking->total_price        = $booking->total_price + $extraServicePrice;
                $booking->end_at             = $newEndTime->toTimeString();
                $booking->modify_time        = $modifyTime;
                $bookingService->modify_time = $modifyTime;
                $booking->save();
                $bookingService->save();

                foreach ($extraServices as $extraService) {
                    $bookingExtraService = new BookingExtraService;
                    $bookingExtraService->fill([
                        'date'     => $booking->date,
                        'tmp_uuid' => $booking->uuid,
                    ]);
                    $bookingExtraService->booking()->associate($booking);
                    $bookingExtraService->extraService()->associate($extraService);
                    $bookingExtraService->save();
                }
            } catch(\Exception $ex){
                $data['success'] = false;
                $data['message'] = $ex->getMessage();
            }
        } else {
            $data['success'] = false;
            $data['message'] = trans('as.bookings.error.not_enough_slots');
            $data['status']  = 500;
        }
        return $data;
    }

    public function changeStatus()
    {
        $bookingId   = Input::get('booking_id');
        $status_text = Input::get('booking_status');
        $data = [];
        try{
            $booking = Booking::ofCurrentUser()->find($bookingId);
            $status  =  $booking->getStatus($status_text);
            $booking->setStatus($status_text);

            if ($status === Booking::STATUS_CANCELLED) {
                $booking->delete_reason = 'Cancelled by admin';
                $booking->save();
                $booking->delete();
            } else {
                $booking->save();
            }

            $data['success'] = true;
        } catch (\Exception $ex){
            $data['message'] = $ex->getMessage();
            $data['success'] = false;
        }
        return $data;
    }

    /**
     * Add new temp service to booking
     *
     * @return json
     */
    public function addBookingService()
    {
        $serviceId           = (int) Input::get('service_id');
        $bookingId           = (int) Input::get('booking_id');//if update old booking
        $employeeId          = (int) Input::get('employee_id');
        $serviceTimeId       = Input::get('service_time', 'default');
        $modifyTime          = (int) Input::get('modify_times', 0);
        $hash                = Input::get('hash');
        $bookingDate         = Input::get('booking_date');
        $startTimeStr        = Input::get('start_time');
        $uuid                = Input::get('uuid', '');// from ajax uuid
        $isRequestedEmployee = Input::get('is_requested_employee', false);

        if(empty($serviceId) || empty($serviceTimeId))
        {
            $data['success'] = false;
            $data['message'] = trans('as.bookings.error.service_empty');
            return Response::json($data, 500);
        }

        $bookingService = (empty($bookingId))
            ? BookingService::where('tmp_uuid', $uuid)->first()
            : BookingService::where('booking_id', $bookingId)->first();

        try{
            $employee = Employee::ofCurrentUser()->find($employeeId);
            $service = Service::ofCurrentUser()->find($serviceId);

            $length = 0;
            $serviceTime = null;
            if ($serviceTimeId === 'default') {
                $service = Service::ofCurrentUser()->find($serviceId);
                $length = $service->length;
            } else {
                $serviceTime = ServiceTime::find($serviceTimeId);
                $length = $serviceTime->length;
            }

            $plustime = $employee->getPlustime($service->id);

            $endTimeDelta = ($length + $modifyTime + $plustime);
            if ($endTimeDelta < 1) {
                $data['message'] = trans('as.bookings.error.empty_total_time');
                return Response::json($data, 400);
            }

            $startTime = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $bookingDate, $startTimeStr));
            $endTime   = with(clone $startTime)->addMinutes($endTimeDelta);
            $endDay    = with(clone $startTime)->hour(23)->minute(59)->second(59);

            //Check if the overbook end time exceed the current working day.
            if($endTime > $endDay)
            {
                $data['message'] = trans('as.bookings.error.exceed_current_day');
                return Response::json($data, 400);
            }

            //Check if the book overllap with employee freetime
            $isOverllapedWithFreetime = $employee->isOverllapedWithFreetime($bookingDate, $startTime, $endTime);
            if($isOverllapedWithFreetime)
            {
                $data['message'] = trans('as.bookings.error.overllapped_with_freetime');
                return Response::json($data, 400);
            }

            //Check is there any existed booking with this service time
            $isBookable = Booking::isBookable($employeeId, $bookingDate, $startTime, $endTime, $uuid);

            //TODO Check overlapped booking in user cart

            if (!$isBookable) {
                $data['message'] = trans('as.bookings.error.add_overlapped_booking');
                return Response::json($data, 400);
            }

            $areResourcesAvailable = Booking::areResourcesAvailable($employeeId, $service, $bookingDate, $startTime, $endTime);

            if(!$areResourcesAvailable) {
                $data['message'] = trans('as.bookings.error.not_enough_resources');
                return Response::json($data, 400);
            }

            //TODO validate modify time and service time
            $model = (empty($bookingService)) ? (new BookingService) : $bookingService;

            //Using uuid for retrieve it later when insert real booking
            $model->fill([
                'tmp_uuid'              => $uuid,
                'date'                  => $bookingDate,
                'modify_time'           => $modifyTime,
                'start_at'              => $startTimeStr,
                'end_at'                => $endTime,
                'is_requested_employee' => $isRequestedEmployee
            ]);

            //there is no method opposite with associate
            $model->service_time_id = null;

            if (!empty($serviceTime)) {
                $model->serviceTime()->associate($serviceTime);
            }
            $model->service()->associate($service);
            $model->user()->associate($this->user);
            $model->employee()->associate($employee);
            $model->save();
            $price = isset($service) ? $service->price : $serviceTime->price;

            $data = [
                'datetime'      => $startTime->toDateTimeString(),
                'price'         => $price,
                'service_name'  => $service->name,
                'modify_time'   => $modifyTime,
                'plustime'      => $plustime,
                'employee_name' => $employee->name,
                'uuid'          => $uuid
            ];

            $cart = [
                'datetime'      => $startTime->toDateString(),
                'price'         => $price,
                'service_name'  => $service->name,
                'employee_name' => $employee->name,
                'start_at'      => $startTimeStr,
                'end_at'        => $endTime->format('H:i'),
                'uuid'          => $uuid
            ];
        } catch (\Watson\Validating\ValidationException $ex){
            $data = [];
            $data['success'] = false;
            $data['message'] = $ex->getErrors();
        }
        return Response::json($data);
    }

    /**
     * Add/Edit a booking
     *
     **/
    public function upsertBooking()
    {
        $uuid                = Input::get('booking_uuid');
        $bookingId           = Input::get('booking_id');
        $bookingStatus       = Input::get('booking_status');
        $notes               = Input::get('booking_notes');
        $isRequestedEmployee = Input::get('is_requested_employee', false);

        try {
            //support multiple service time?
            $bookingService = (empty($bookingId))
                ? BookingService::where('tmp_uuid', $uuid)->first()
                : BookingService::where('booking_id',$bookingId)->first();
            $data = [];

            if(empty($bookingService)){
                $data['success'] = false;
                $data['message'] = trans('as.bookings.missing_services');
                return Response::json($data);
            }
            $employee = $bookingService->employee;
            $service  = $bookingService->service;

            $consumer = $this->handleConsumer();

            $length = (!empty($bookingService->serviceTime->length))
                    ? $bookingService->serviceTime->length
                    : $bookingService->service->length;

            $price = (!empty($bookingService->serviceTime->price))
                    ? $bookingService->serviceTime->price
                    : $bookingService->service->price;

            $plustime = $employee->getPlustime($service->id);

            $status = Booking::getStatus($bookingStatus);

            $total = $length + $plustime + $bookingService->modify_time;
            $total_price = $price;

            $booking = new Booking();
            $startTime = null;
            $endTime = null;

            if(!empty($bookingId)){
                $booking = Booking::find($bookingId);
                $bookingExtraServices = $booking->extraServices;

                $extraServiceTime  = 0;
                $extraServicePrice = 0;
                $extraServices = [];

                foreach ($bookingExtraServices as $extraService) {
                    $extraServiceTime  += $extraService->length;
                    $extraServicePrice += $extraService->price;
                    $extraServices[] = $extraService;
                }

                $date      = new Carbon($booking->date);
                $startTime = Carbon::createFromFormat('Y-m-d H:i:s', sprintf('%s %s', $booking->date, $booking->start_at));

                $total = $length + $plustime + $extraServiceTime + $bookingService->modify_time;
                $total_price = $price + $extraServicePrice;

                $endTime= with(clone $startTime)->addMinutes($total);
            } else {
                $startTime = Carbon::createFromFormat('Y-m-d H:i:s', sprintf('%s %s', $bookingService->date, $bookingService->start_at));
                $endTime   = Carbon::createFromFormat('Y-m-d H:i:s', sprintf('%s %s', $bookingService->date, $bookingService->end_at));
            }

            $booking->fill([
                'date'        => $bookingService->date,
                'start_at'    => $startTime->toTimeString(),
                'end_at'      => $endTime->toTimeString(),
                'total'       => $total,
                'total_price' => $total_price,
                'status'      => $status,
                'notes'       => $notes,
                'uuid'        => $uuid,
                'modify_time' => $bookingService->modify_time,
                'plustime'    => $plustime,
                'ip'          => Request::getClientIp(),
                'source'      => 'backend'
            ]);
            //need to update end_at, total when add extra service

            $booking->consumer()->associate($consumer);
            $booking->user()->associate($this->user);
            $booking->employee()->associate($bookingService->employee);
            if($status === Booking::STATUS_CANCELLED){
                $booking->delete_reason = 'Cancelled while updating';
                $booking->save();
                $booking->delete();
            } else {
                $booking->save();
            }
            //Users can check this option before or after save a booking service
            $bookingService->is_requested_employee = $isRequestedEmployee;
            $bookingService->booking()->associate($booking);
            $bookingService->save();

            //Don't send sms when update booking
            if(empty($bookingId)){
                //Only can send sms after insert booking service
                $booking->attach(new SmsObserver(true));//true is backend
                $booking->notify();
            }

            $data['success']      = true;
            $data['baseURl']     = route('as.index');
            $data['bookingDate'] = $booking->date;
        } catch (\Watson\Validating\ValidationException $ex) {
            $data['success'] = false;
            $data['message'] = Util::getHtmlListError($ex);
        }

        return Response::json($data);
    }

    /**
     * Insert new consumer if not exist in db yet
     *
     * @return Consumer
     */
    protected function handleConsumer()
    {
        //TODO suggest user info in front end

        //Insert customer
        $first_name = Input::get('first_name', '');
        $last_name  = Input::get('last_name', '');
        $email      = Input::get('email', '');
        $phone      = Input::get('phone', '');
        $address    = Input::get('address', '');
        $hash       = Input::get('hash');

         //TODO handle consumer validation
        $data = [
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'email'      => $email,
            'phone'      => $phone,
            'address'    => $address,
            'hash'       => $hash
        ];

        $consumer = AsConsumer::handleConsumer($data, $this->user);

        return $consumer;
    }

    public function removeBookingServiceInCart()
    {
        $uuid           = Input::get('uuid');
        $hash           = Input::get('hash');
        $cartId         = Input::get('cart_id');
        $cartDetailId   = Input::get('cart_detail_id');

        try {
            $bookingService      = BookingService::where('tmp_uuid', $uuid)->delete();
            $bookingExtraService = BookingExtraService::where('tmp_uuid', $uuid)->delete();
            $cart = Cart::find($cartId);
            // $cart->delete();
            $cartDetail = CartDetail::find($cartDetailId);
            $cartDetail->delete();

            $data['success'] = true;
            if(empty($cart->details()->count())){
                $data['success_url'] = route('as.embed.embed', ['hash'=> $hash]);
            }
        } catch (\Exception $ex) {
            $data['message'] = $ex->getMessage();
            return Response::json($data, 400);
        }
        return Response::json($data);
    }


    public function removeExtraService()
    {
        $extraServiceId  = Input::get('extra_service');
        $bookingId       = Input::get('booking_id');
        $booking         = Booking::ofCurrentUser()->find($bookingId);
        $data = [];
        try {
            $bookingExtraService = BookingExtraService::where('extra_service_id', $extraServiceId)
                ->where('booking_id', $bookingId)
                ->firstOrFail();

            if(!empty($bookingExtraService)){
                $bookingExtraService->delete();
            }
            $bookingService = $booking->bookingServices()->first();
            //Recalculate end time and total price
            $total = $price = 0;
            if(!empty($bookingService)){
                $total = (!empty($bookingService->serviceTime->length))
                        ? $bookingService->serviceTime->length
                        : $bookingService->service->length;

                $total += $bookingService->modify_time;

                $price = (!empty($bookingService->serviceTime->price))
                        ? $bookingService->serviceTime->price
                        : $bookingService->service->price;
            }

            $extraServices = $booking->extraServices;
            foreach ($extraServices as $extraService) {
                $total += $extraService->length;
                $price  += $extraService->price;
            }

            $startTime   = Carbon::createFromFormat('Y-m-d H:i:s', sprintf('%s %s', $booking->date, $booking->start_at));
            $newEndTime= with(clone $startTime)->addMinutes($total);

            $booking->total        = $total;
            $booking->total_price  = $price;
            $booking->end_at       = $newEndTime->toTimeString();
            $booking->save();

            $data['success'] = true;
        } catch (\Exception $ex) {
            $data['success'] = false;
            $data['message'] = $ex->getMessage();
            return Response::json($data, 500);
        }
        return Response::json($data);
    }

    /**
     * Search an employee by name, email, phone
     * @TODO use full text search instead of LIKE clause
     *
     * @return json
     */
    public function searchConsumer()
    {
        $keyword = Input::get('keyword');
        $consumers = Confide::user()->consumers()
                    ->where('is_visible', 1)
                    ->where(function($q) use ($keyword) {
                        $q->where('consumers.first_name', 'like', '%' . $keyword . '%')
                            ->orWhere('consumers.last_name', 'like', '%' . $keyword . '%')
                            ->orWhere('consumers.email', 'like', '%' . $keyword . '%')
                            ->orWhere('consumers.phone', 'like', '%' . $keyword . '%');
                    })
                ->get();
        $data = [];
        foreach($consumers as $consumer){
            $data[] = array(
                'id'         => $consumer->id,
                'text'       => $consumer->name,
                'first_name' => $consumer->first_name,
                'last_name'  => $consumer->last_name,
                'email'      => $consumer->email,
                'phone'      => $consumer->phone,
                'address'    => $consumer->address
            );
        }
        return Response::json($data);
    }

    /**
     * @overwrite
     */
    public function delete($id)
    {
        $item = $this->getModel()->ofCurrentUser()->findOrFail($id);
        $item->delete_reason = Input::get('reason');
        $item->save();
        $item->delete();

        if (Request::ajax() === true) {
            return Response::json(['success' => true]);
        }

        return Redirect::route(static::$crudRoutes['index'])
            ->with(
                'messages',
                $this->successMessageBag(trans('as.crud.success_delete'))
            );
    }
}
