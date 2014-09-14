<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response, Util, Hashids, Session, Request, Mail, Sms;
use Illuminate\Support\Collection;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\AsConsumer;
use App\Consumers\Models\Consumer;
use App\Core\Models\User;
use Carbon\Carbon;
use App\Appointment\Models\Observer\EmailObserver;
use App\Appointment\Models\Observer\SmsObserver;

class Bookings extends AsBase
{
    use App\Appointment\Traits\Crud;
    protected $viewPath   = 'modules.as.bookings';
    protected $langPrefix = 'as.bookings';
    protected $crudShowTab = false;

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

        $booking              = Booking::find($bookingId);
        $bookingStatuses      = Booking::getStatuses();
        $employee             = $booking->employee;
        $services             = $employee->services;
        $bookingService       = $booking->bookingServices()->first();
        $bookingExtraServices = $booking->extraServices()->get();

        $categories = [];
        $categories[-1] = trans('common.select');
        foreach ($services as $service) {
            //for getting distinct categories
            $categories[$service->category->id] = $service->category->name;
        }

        $bookingCategoryId   = $bookingService->service->category->id;
        $bookingServiceId    = $bookingService->service->id;
        $bookingServices     = $employee->services()->where('category_id', $bookingCategoryId)->lists('name','id');
        $bookingServices[-1] = trans('common.select');
        ksort($bookingServices);//sort selected services by key

        $serviceTimes = $bookingService->service->serviceTimes;
        $serviceTimesList = [];
        $serviceTimesList['default'] = sprintf('%s (%s)', $bookingService->service->length, $bookingService->service->description);
        foreach ($serviceTimes as $serviceTime) {
            $serviceTimesList[$serviceTime->id] = $serviceTime->length;
        }

        $bookingServiceTime = (!empty($booking->bookingServices()->first()->serviceTime))
                            ? $booking->bookingServices()->first()->serviceTime->id
                            : 'default';

        return View::make('modules.as.bookings.form', [
            'booking'               => $booking,
            'uuid'                  => $booking->uuid,
            'modifyTime'            => $booking->modify_time,
            'bookingDate'           => $booking->date,
            'startTime'             => $booking->start_at,
            'endTime'               => $booking->end_at,
            'bookingStatuses'       => $bookingStatuses,
            'bookingService'        => $bookingService,
            'bookingServiceTime'    => $bookingServiceTime,
            'bookingExtraServices'  => $bookingExtraServices,
            'employee'              => $employee,
            'category_id'           => $bookingCategoryId,
            'service_id'            => $bookingServiceId,
            'categories'            => $categories,
            'services'              => $bookingServices,
            'serviceTimes'          => array_values($serviceTimesList),
        ]);
    }

    private function getBlankBookingForm()
    {
        $employeeId  = (int) Input::get('employee_id');
        $bookingDate = Input::get('booking_date');
        $startTime   = Input::get('start_time');

        $bookingStatuses = Booking::getStatuses();

        $employee = Employee::find($employeeId);
        $services = $employee->services;
        $categories = [];
        $categories[-1] = trans('common.select');
        foreach ($services as $service) {
            //for getting distinct categories
            $categories[$service->category->id] = $service->category->name;
        }

        return View::make('modules.as.bookings.form', [
            'uuid'            => Util::uuid(),
            'employee'        => $employee,
            'categories'      => $categories,
            'bookingDate'     => $bookingDate,
            'startTime'       => $startTime,
            'bookingStatuses' => $bookingStatuses
        ]);
    }

    public function getAddExtraServiceForm(){
        $bookingId            = Input::get('booking_id');
        $booking              = Booking::find($bookingId);//TODO if not found booking

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

    public function getChangeStatusForm(){
        $bookingId = Input::get('booking_id');
        $booking = Booking::find($bookingId);
        $bookingStatuses = Booking::getStatuses();
        //TODO if not found booking
        return View::make('modules.as.bookings.changeStatus', [
            'bookingStatuses' => $bookingStatuses,
            'booking'         => $booking
        ]);
    }

    public function changeStatus(){
        $bookingId   = Input::get('booking_id');
        $status_text = Input::get('booking_status');
        $data = [];
        try{
            $booking = Booking::find($bookingId);
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
        $employee = Employee::find($employeeId);
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
        $service      = Service::find($serviceId);
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
        $employeeId     = (int) Input::get('employee_id');
        $serviceTimeId  = Input::get('service_time', 'default');
        $modifyTime     = (int) Input::get('modify_times', 0);
        $hash           = Input::get('hash');
        $bookingDate    = Input::get('booking_date');
        $startTimeStr   = Input::get('start_time');
        $uuid           = Input::get('uuid', Util::uuid());

        $employee = Employee::find($employeeId);
        $service = Service::find($serviceId);

        $length = 0;
        if ($serviceTimeId === 'default') {
            $service = Service::find($serviceId);
            $length = $service->length;
        } else {
            $serviceTime = ServiceTime::find($serviceTimeId);
            $length = $serviceTime->length;
        }

        $endTimeDelta = ($length + $modifyTime);
        $startTime = Carbon::createFromFormat('Y-m-d H:i', sprintf('%s %s', $bookingDate, $startTimeStr));
        $endTime = with(clone $startTime)->addMinutes($endTimeDelta);

        //Check is there any existed booking with this service time
        $bookings = Booking::where('date', $bookingDate)
            ->where('employee_id', $employeeId)
            ->whereNull('deleted_at')
            ->where(function ($query) use ($startTime, $endTime) {
                return $query->where(function ($query) use ($startTime) {
                    return $query->where('start_at', '<=', $startTime->toTimeString())
                         ->where('end_at', '>', $startTime->toTimeString());
                })->orWhere(function ($query) use ($endTime) {
                     return $query->where('start_at', '<', $endTime->toTimeString())
                          ->where('end_at', '>=', $endTime->toTimeString());
                })->orWhere(function ($query) use ($startTime, $endTime) {
                     return $query->where('start_at', '=', $startTime->toTimeString())
                          ->where('end_at', '=', $endTime->toTimeString());
                });
            })->get();
        //TODO Check overlapped booking in user cart

        //Check enough timeslot in employee default working time
        list($endHour, $endMinute) = explode(':', $employee->getTodayDefaultEndAt($startTime->dayOfWeek));
        $endAt = with(clone $endTime)->setTime($endHour, $endMinute, 0);
        if ($endTime > $endAt) {
            $data['message'] = trans('as.bookings.error.insufficient_slots');
            return Response::json($data, 400);
        }

        if (!$bookings->isEmpty()) {
            $data['message'] = trans('as.bookings.error.add_overlapped_booking');
            return Response::json($data, 400);
        }
        //TODO validate modify time and service time
        $bookingService = new BookingService();
        //Using uuid for retrieve it later when insert real booking
        $bookingService->fill([
            'tmp_uuid'    => $uuid,
            'date'        => $bookingDate,
            'modify_time' => $modifyTime,
            'start_at'    => $startTimeStr,
            'end_at'      => $endTime
        ]);

        if (!empty($serviceTime)) {
            $bookingService->serviceTime()->associate($serviceTime);
        }
        $bookingService->service()->associate($service);
        $bookingService->user()->associate($this->user);
        $bookingService->employee()->associate($employee);
        $bookingService->save();
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

        return Response::json($data);
    }

    /**
     * Add new booking to database
     *
     **/
    public function addBooking()
    {
        $uuid          = Input::get('booking_uuid');
        $bookingId     = Input::get('booking_id');
        $bookingStatus = Input::get('booking_status');
        $notes         = Input::get('booking_notes');

        try {
            //support multiple service time?
            $bookingService = BookingService::where('tmp_uuid', $uuid)->firstOrFail();
            $data = [];

            $consumer = $this->handleConsumer();

            $length = (isset($bookingService->serviceTime))
                    ? $bookingService->serviceTime->length
                    : $bookingService->service->length;

            $status = Booking::getStatus($bookingStatus);

            $booking = new Booking();
            $booking->fill([
                'date'        => $bookingService->date,
                'start_at'    => $bookingService->start_at,
                'end_at'      => $bookingService->end_at,
                'total'       => $length,
                'status'      => $status,
                'notes'       => $notes,
                'uuid'        => $uuid,
                'modify_time' => $bookingService->modify_time,
                'ip'          => Request::getClientIp(),
            ]);
            //need to update end_at, total when add extra service

            $booking->consumer()->associate($consumer);
            $booking->user()->associate($this->user);
            $booking->employee()->associate($bookingService->employee);
            $booking->save();
            $bookingService->booking()->associate($booking);
            $bookingService->save();
            $data['status']      = true;
            $data['baseURl']     = route('as.index');
            $data['bookingDate'] = $booking->date;
            Session::forget('carts');
        } catch (\Exception $ex) {
            $data['status'] = false;
            $data['message'] = $ex->getMessage();
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
        $hash      =  Input::get('hash');
        $consumer = Consumer::where('email', $email)->first();
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

        return $consumer;
    }

    /**
     * Handle ajax request to delete given booking service
     * that was added to booking form
     *
     * @return json
     */
    public function removeBookingService()
    {
        $uuid = Input::get('uuid');
        try {
            $bookingService = BookingService::where('tmp_uuid', $uuid)->delete();
            $data['success'] = true;
        } catch (\Exception $ex) {
            $data['message'] = $ex->getMessage();

            return Response::json($data, 400);
        }

        return Response::json($data);
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

    public function addExtraServices()
    {
        $extraServiceIds = Input::get('extra_services');
        $bookingId       = Input::get('booking_id');
        $booking         = Booking::find($bookingId);

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

        $isBookable = $booking->isBookable($date, $endTime, $newEndTime);

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
                'address'    => $consumer->address
            );
        }
        return Response::json($data);
    }
}
