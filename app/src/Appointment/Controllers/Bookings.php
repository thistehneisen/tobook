<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response, DB;
use Util, Hashids, Session, Request, Mail, Sms;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Core\Models\User;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeService;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\ExtraService;
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
            });
        return $query;
    }

    public function getBookingData($bookingId)
    {
        $booking              = Booking::ofCurrentUser()->find($bookingId);
        $bookingStatuses      = Booking::getStatuses();
        $employee             = $booking->employee;
        $services             = $employee->services;
        $bookingService       = $booking->bookingServices()->first();
        $bookingExtraServices = $booking->extraServices()->get();

        list($categories, $jsonServices, $jsonServiceTimes) = $this->servicesServiceTimesJson($services);

        $bookingCategoryId   = (!empty($bookingService)) ? $bookingService->service->category->id : null;
        $bookingServiceId    = (!empty($bookingService)) ? $bookingService->service->id : null;
        $bookingServices     = $employee->services()->where('category_id', $bookingCategoryId)->lists('name','id');
        $bookingServices[-1] = trans('common.select');
        ksort($bookingServices);//sort selected services by key

        $serviceTimes = (!empty($bookingService)) ? $bookingService->service->serviceTimes : [];
        $serviceTimesList = [];
        $length = (!empty($bookingService)) ? $bookingService->service->length : 0;
        $description = (!empty($bookingService)) ? $bookingService->service->description : '';
        $serviceTimesList['default'] = sprintf('%s (%s)', $length, $description);
        foreach ($serviceTimes as $serviceTime) {
            $serviceTimesList[$serviceTime->id] = $serviceTime->length;
        }

        $bookingServiceTime = (!empty($booking->bookingServices()->first()->serviceTime->id))
                            ? $booking->bookingServices()->first()->serviceTime->id
                            : 'default';

        $serviceTimes = (!empty($jsonServiceTimes[$bookingServiceId]))
                ? $jsonServiceTimes[$bookingServiceId]
                : [];
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
            'jsonServices'          => json_encode($jsonServices),
            'jsonServiceTimes'      => json_encode($jsonServiceTimes),
            'services'              => $bookingServices,
            'serviceTimes'          => $serviceTimes,
        ];
    }
    /**
     * Handle ajax request to display booking form
     *
     * @return View
     **/
    public function getBookingForm()
    {
        $bookingId   = (int) Input::get('booking_id');

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

        list($categories, $jsonServices, $jsonServiceTimes) = $this->servicesServiceTimesJson($services);

        return View::make('modules.as.bookings.form', [
            'uuid'            => Util::uuid(),
            'employee'        => $employee,
            'categories'      => $categories,
            'jsonServices'    => json_encode($jsonServices),
            'jsonServiceTimes'=> json_encode($jsonServiceTimes),
            'bookingDate'     => $bookingDate,
            'startTime'       => $startTime,
            'bookingStatuses' => $bookingStatuses
        ]);
    }

    private function servicesServiceTimesJson($services)
    {
        $categories[-1]   = trans('common.select');
        $jsonServices     = [];
        $jsonServiceTimes = [];
        foreach ($services as $service) {
            //for getting distinct categories
            $categories[$service->category->id] = $service->category->name;
            $jsonServices[$service->category->id][0] = [
                'id' => -1,
                'name'=> trans('common.select'),
            ];

            $jsonServices[$service->category->id][] = [
                'id'   => $service->id,
                'name' =>$service->name,
            ];
            $jsonServiceTimes[$service->id][] = [
                'id'  => -1,
                'name'=> trans('common.select'),
                'length' => 0
            ];
            $jsonServiceTimes[$service->id][] = [
                'id'            => 'default',
                'name'          => $service->length,
                'length'        => $service->length,
                'description'   => $service->description,
            ];
            foreach ($service->serviceTimes as $serviceTime) {
                $jsonServiceTimes[$service->id][] = [
                    'id'            => $serviceTime->id,
                    'name'          => $serviceTime->length,
                    'length'        => $serviceTime->length,
                    'description'   => $serviceTime->description,
                ];
            }
        }
        return [$categories, $jsonServices, $jsonServiceTimes];
    }

    public function getAddExtraServiceForm()
    {
        $bookingId = Input::get('booking_id');
        $booking   = Booking::ofCurrentUser()->find($bookingId);//TODO if not found booking

        $bookingExtraServices = $booking->extraServices()->lists('extra_service_id');

        $extraServices = $booking->bookingServices()
                            ->first()->service
                            ->extraServices();
        if(!empty($bookingExtraServices)){
            $data = $extraServices->whereNotIn('as_extra_services.id', $bookingExtraServices);
        }

        $data = $extraServices->lists('name', 'id');

        return View::make('modules.as.bookings.extraService', [
            'booking'       => $booking,
            'extraServices' => $data
        ]);
    }

    public function getChangeStatusForm()
    {
        $bookingId = Input::get('booking_id');
        $booking = Booking::ofCurrentUser()->find($bookingId);
        $bookingStatuses = Booking::getStatuses();
        //TODO if not found booking
        return View::make('modules.as.bookings.changeStatus', [
            'bookingStatuses' => $bookingStatuses,
            'booking'         => $booking
        ]);
    }

    public function changeStatus()
    {
        $bookingId   = Input::get('booking_id');
        $status_text = Input::get('booking_status');
        $data = [];
        try{
            $booking = Booking::ofCurrentUser()->find($bookingId);
            $status =  $booking->getStatus($status_text);
            $booking->setStatus($status_text);
            if($status === Booking::STATUS_CANCELLED){
                $booking->delete();
            } else {
                $booking->save();
            }
            $data['success'] = true;
        } catch (\Exception $ex){
            $data['message'] = $ex->getMessage();
            $data['false'] = true;
        }
        return Response::json($data);
    }

    /**
     *  Handle ajax request to return services by certain employee and category
     *
     *  @return json
     **/
    public function getEmployeeServicesByCategory()
    {
        $categoryId = (int) Input::get('category_id');
        $employeeId = (int) Input::get('employee_id');
        $employee = Employee::ofCurrentUser()->find($employeeId);
        $services = $employee->services()->where('category_id', $categoryId)->get();
        $data = [];
        foreach ($services as $service) {
            $data[$service->id] = [
                'id'   => $service->id,
                'name' => $service->name
            ];
        }

        return Response::json(array_values($data));
    }

    /**
     *  Handle ajax request to return service times in booking form
     *
     *  @return json
     **/
    public function getServiceTimes()
    {
        $serviceId    = (int) Input::get('service_id');
        $service      = Service::ofCurrentUser()->find($serviceId);
        $serviceTimes = $service->serviceTimes;
        $data = [];
        $data['default'] = [
            'id' => 'default',
            'length' => sprintf('%s (%s)', $service->length, $service->description)
        ];
        foreach ($serviceTimes as $serviceTime) {
            $data[$serviceTime->id] = [
                'id'     => $serviceTime->id,
                'length' => $serviceTime->length
            ];
        }

        return Response::json(array_values($data));
    }

    /**
     * Add new temp service to booking
     *
     * @return json
     */
    public function addBookingService()
    {
        $serviceId      = (int) Input::get('service_id');
        $bookingId      = (int) Input::get('booking_id');//if update old booking
        $employeeId     = (int) Input::get('employee_id');
        $serviceTimeId  = Input::get('service_time', 'default');
        $modifyTime     = (int) Input::get('modify_times', 0);
        $hash           = Input::get('hash');
        $bookingDate    = Input::get('booking_date');
        $startTimeStr   = Input::get('start_time');
        $uuid           = Input::get('uuid', '');// from ajax uuid


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
            //TODO validate modify time and service time
            $model = (empty($bookingService)) ? (new BookingService) : $bookingService;

            //Using uuid for retrieve it later when insert real booking
            $model->fill([
                'tmp_uuid'    => $uuid,
                'date'        => $bookingDate,
                'modify_time' => $modifyTime,
                'start_at'    => $startTimeStr,
                'end_at'      => $endTime
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

            $carts = Session::get('carts', []);
            $carts[$uuid] = $cart;
            Session::put('carts' , $carts);
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
        $uuid          = Input::get('booking_uuid');
        $bookingId     = Input::get('booking_id');
        $bookingStatus = Input::get('booking_status');
        $notes         = Input::get('booking_notes');

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

                $total = $length + $extraServiceTime + $bookingService->modify_time;
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
            ]);
            //need to update end_at, total when add extra service

            $booking->consumer()->associate($consumer);
            $booking->user()->associate($this->user);
            $booking->employee()->associate($bookingService->employee);
            $booking->save();


            $bookingService->booking()->associate($booking);
            $bookingService->save();

            //Don't send sms when update booking
            if(empty($bookingId)){
                //Only can send sms after insert booking service
                $booking->attach(new SmsObserver(true));//true is backend
                $booking->notify();
            }

            Session::forget('carts');
            Session::forget('booking_info');

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
        $firstname = Input::get('firstname', '');
        $lastname  = Input::get('lastname', '');
        $email     = Input::get('email', '');
        $phone     = Input::get('phone', '');
        $address   = Input::get('address', '');
        $hash      = Input::get('hash');

        $consumer = Consumer::where('first_name', $firstname)
            ->where('last_name', $lastname)
            ->where('phone', $phone)->first();

        $asConsumer = new AsConsumer();

        //In front end, user is identified from hash
        $user = $this->user;
        $userId = null;
        if(empty($this->user)){
            $decoded = Hashids::decrypt($hash);
            if(empty($decoded)){
                return;
            }
            $user = User::find($decoded[0]);
            $userId = $decoded[0];
        }

        //TODO handle consumer validation
        $data = [
            'first_name' => $firstname,
            'last_name'  => $lastname,
            'email'      => $email,
            'phone'      => $phone,
            'address'    => $address
        ];
        try{
            if (empty($consumer->id)) {
                $consumer = Consumer::make($data, $userId);
                $asConsumer->user()->associate($user);
                $asConsumer->consumer()->associate($consumer);
                $asConsumer->save();
            } else {
                //TODO update consumer
                $consumer->fill($data);
                $consumer->saveOrFail();
            }
        } catch(\Watson\Validating\ValidationException $ex){
            throw $ex;
        }
        return $consumer;
    }

    public function removeBookingServiceInCart()
    {
        $uuid = Input::get('uuid');
        $hash = Input::get('hash');
        try {
            $bookingService      = BookingService::where('tmp_uuid', $uuid)->delete();
            $bookingExtraService = BookingExtraService::where('tmp_uuid', $uuid)->delete();
            $carts = Session::get('carts', []);
            unset($carts[$uuid]);
            Session::put('carts' , $carts);
            $data['success'] = true;
            if(empty($carts)){
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

    public function addExtraServices()
    {
        $extraServiceIds = Input::get('extra_services');
        $bookingId       = Input::get('booking_id');
        $booking         = Booking::ofCurrentUser()->find($bookingId);

        if(empty($extraServiceIds)){
            return Response::json([
                'success'=> false,
                'message'=> 'as.bookings.select_extra_services',
            ]);
        }

        $extraServiceTime  = 0;
        $extraServicePrice = 0;
        $extraServices = [];

        foreach ($extraServiceIds as $id) {
            $extraService = ExtraService::find($id);
            $extraServiceTime  += $extraService->length;
            $extraServicePrice += $extraService->price;
            $extraServices[] = $extraService;
        }
        $date      = new Carbon($booking->date);
        $endTime   = Carbon::createFromFormat('Y-m-d H:i:s', sprintf('%s %s', $booking->date, $booking->end_at));
        $newEndTime= with(clone $endTime)->addMinutes($extraServiceTime);

        $isBookable = Booking::isBookable($booking->employee->id, $date, $endTime, $newEndTime, $booking->uuid);

        if($isBookable)
        {
            $booking->total        = $booking->total + $extraServiceTime;
            $booking->total_price  = $booking->total_price + $extraServicePrice;
            $booking->end_at       = $newEndTime->toTimeString();
            $booking->save();

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
        } else {
            return Response::json([
                'success'=> false,
                'message'=> 'as.bookings.not_enough_slots',
            ]);
        }

        return Response::json([
            'success'=> true,
            'message'=> 'as.bookings.add_extra_services_success',
        ]);
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
        $consumers = Consumer::join('as_consumers', 'as_consumers.consumer_id', '=', 'consumers.id')
                    ->where('as_consumers.user_id', Confide::user()->id)
                    ->where(function($q) use ($keyword) {
                        $q->where('consumers.first_name', 'like', '%' . $keyword . '%')
                            ->orWhere('consumers.last_name', 'like', '%' . $keyword . '%')
                            ->orWhere('consumers.email', 'like', '%' . $keyword . '%')
                            ->orWhere('consumers.phone', 'like', '%' . $keyword . '%');
                    })
                ->select('as_consumers.id', 'consumers.first_name', 'consumers.last_name', 'consumers.email', 'consumers.phone', 'as_consumers.updated_at')
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
}
