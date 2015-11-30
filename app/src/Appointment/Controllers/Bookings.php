<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response, DB, Cart, Event;
use Util, Hashids, Session, Request, Mail, Sms;
use Carbon\Carbon;
use App\Core\Models\User;
use App\Core\Models\CartDetail;
use App\Core\Models\CouponBooking;
use App\Consumers\Models\Consumer;
use App\Appointment\Models\Booking;
use App\Appointment\Models\BookingService;
use App\Appointment\Models\BookingExtraService;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\Reception\BackendReceptionist;

class Bookings extends AsBase
{
    use \CRUD;
    protected $viewPath = 'modules.as.bookings';
    protected $crudOptions = [
        'modelClass'   => 'App\Appointment\Models\Booking',
        'langPrefix'   => 'as.bookings',
        'layout'       => 'modules.as.layout',
        'showTab'      => false,
        'deleteReason' => true,
        'bulkActions'  => [],
        'indexFields'  => ['uuid', 'date', 'consumer', 'total', 'notes', 'status'],
        'presenters'   => [
            'consumer' => ['App\Appointment\Controllers\Bookings', 'presentConsumer'],
            'status'   => ['App\Appointment\Controllers\Bookings', 'presentStatus'],
            'date'     => ['App\Appointment\Controllers\Bookings', 'presentDate'],
        ],
    ];

    public static function presentConsumer($value, $item)
    {
        return isset($item->consumer->name) ? $item->consumer->name : '';
    }

    public static function presentStatus($value, $item)
    {
        return trans('as.bookings.' . Booking::getStatusByValue($value));
    }

    public static function presentDate($value, $item)
    {
        return str_standard_to_local($value);
    }

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
        $fillable = with(new Consumer())->fillable;
        $table    = with(new Consumer())->getTable();
        $query->join('consumers', 'consumers.id', '=','as_bookings.consumer_id')
            ->orWhere(function ($subQuery) use ($fillable, $q, $table) {
                foreach ($fillable as $field) {
                    $subQuery = $subQuery->orWhere($table  . '.' . $field, 'LIKE', '%'.$q.'%');
                }

                return $subQuery;
            })->whereNULL('as_bookings.deleted_at')->select(
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

            $data['confirm'] = trans('as.bookings.cancel_confirm');
            $data['uuid']    = $uuid;
            $data['booking'] = $booking;

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

            $limit = (int) $this->user->asOptions['cancel_before_limit'];

            $booking = Booking::where('uuid', $uuid)->first();
            
            $now = Carbon::now();

            if ($now < $booking->startTime->copy()->subHours($limit)) {
                Event::fire('booking.cancelled', [$booking]);

                $booking->status = Booking::STATUS_CANCELLED;
                $booking->delete_reason = 'Cancelled by UUID';
                $booking->save();
                $booking->reminder->delete();
                $booking->delete();

                $msg = str_replace('{BookingID}', $uuid, trans('as.bookings.cancel_message'));
                $msg = str_replace('{Services}', $booking->getServiceInfo(), $msg);
                $data['message'] =  $msg;
            } else {
                $data['message'] = sprintf(trans('as.bookings.error.late_cancellation'), $limit);
            }
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
        $bookingServices      = $booking->bookingServices;
        $firstBookingService  = $booking->bookingServices()->first();
        $bookingExtraServices = $booking->extraServices()->get();

        $categories = $this->getCategories($services);

        $bookingCategoryId       = (!empty($firstBookingService->service->category->id)) ? $firstBookingService->service->category->id : null;
        $bookingServiceId        = (!empty($firstBookingService->service->id)) ? $firstBookingService->service->id : null;
        $bookingServicesList     = $employee->services()->where('category_id', $bookingCategoryId)->lists('name','id');
        $bookingServicesList[-1] = trans('common.select');
        ksort($bookingServicesList);//sort selected services by key

        $serviceTimes = (!empty($firstBookingService)) ? $firstBookingService->service->serviceTimes : [];
        $length = (!empty($firstBookingService)) ? $firstBookingService->service->length : 0;
        $description = (!empty($firstBookingService)) ? $firstBookingService->service->description : '';
        $plustime = (!empty($bookingServiceId)) ? $employee->getPlustime($bookingServiceId) : 0;

        $serviceTimesList = (!empty($firstBookingService)) ? $firstBookingService->service->getServiceTimesData() : [];

        $bookingServiceTime = (!empty($booking->bookingServices()->first()->serviceTime->id))
                            ? $booking->bookingServices()->first()->serviceTime->id
                            : 'default';

        $startAt = with(new Carbon($booking->start_at))->format('H:i');
        $endAt   = with(new Carbon($booking->end_at))->format('H:i');
        $extras  = $booking->getDisplayExtraServices();

        $couponBooking = CouponBooking::find($bookingId);
        $couponApplied = false;
        if (!empty($couponBooking->coupon->campaign->id) ){
            $couponApplied = true;
        }
        
        return [
            'booking'               => $booking,
            'uuid'                  => $booking->uuid,
            'modifyTime'            => $booking->modify_time,
            'bookingDate'           => $booking->date,
            'startTime'             => $startAt,
            'endTime'               => $endAt,
            'totalLength'           => $booking->getFormTotalLength(),
            'totalPrice'            => $booking->total_price,
            'bookingStatuses'       => $bookingStatuses,
            'firstBookingService'   => $firstBookingService,
            'bookingServiceTime'    => $bookingServiceTime,
            'bookingExtraServices'  => $bookingExtraServices,
            'employee'              => $employee,
            'category_id'           => $bookingCategoryId,
            'service_id'            => $bookingServiceId,
            'categories'            => $categories,
            'services'              => $bookingServicesList,
            'bookingServices'       => $bookingServices,
            'serviceTimes'          => $serviceTimesList,
            'plustime'              => $plustime,
            'extras'                => $extras,
            'user'                  => $this->user,
            'couponApplied'         => $couponApplied,
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

        if (empty($bookingId)) {
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
        $extras = [];

        $categories = $this->getCategories($services);

        return View::make('modules.as.bookings.form', [
            'uuid'            => Booking::uuid(),
            'employee'        => $employee,
            'categories'      => $categories,
            'bookingDate'     => $bookingDate,
            'bookingServices' => [],
            'startTime'       => $startTime,
            'bookingStatuses' => $bookingStatuses,
            'extras'          => $extras,
            'user'            => $this->user,
            'couponApplied'   => false,
        ]);
    }

    private function getCategories($services)
    {
        $categories[-1]   = trans('common.select');

        foreach ($services as $service) {
            //for getting distinct categories
            if (empty($service->category->id)) {
                continue;
            }
            $categories[$service->category->id] = $service->category->name;
        }

        return $categories;
    }

    public function getModifyBookingForm()
    {
        $bookingId = Input::get('booking_id');

        try {
            $booking = Booking::ofCurrentUser()->findOrFail($bookingId);
        } catch (\Exception $ex) {
            $data['message'] = trans('as.bookings.error.booking_not_found');

            return Response::json($data, 400);
        }

        $bookingStatuses = Booking::getStatuses();

        $extras = $booking->getDisplayExtraServices();

        $resources = $booking->getBookingResources();

        $totalPrice = (!empty($booking->businessCommission->total_price))
            ? $booking->businessCommission->total_price
            : $booking->total_price;

        return View::make('modules.as.bookings.modifyForm', [
            'booking'              => $booking,
            'totalPrice'           => $totalPrice,
            'extraServices'        => $extras,
            'resources'            => $resources,
            'bookingStatuses'      => $bookingStatuses,
            'modifyTime'           => $booking->modify_time
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
            $message = (!empty($resultStatus['message']))
                ? $resultStatus['message']
                : $resultExtraServices['message'];

            // TODO: this error message is for debugging since it can be various cases
            return Response::json([
                'success'=> false,
                'message'=> $message,
            ]);
        }
    }

    public function handleExtraModifiedTimes()
    {
        $bookingId       = Input::get('booking_id');
        $modifyTime      = Input::get('modify_times');
        $extraServiceIds = Input::get('extra_services');
        try {
            $booking  = Booking::findOrFail($bookingId);

            $isRequestedEmployee = (!empty($booking->bookingServices()->first()->is_requested_employee))
                ? $booking->bookingServices()->first()->is_requested_employee
                : false;
                
            $receptionist = new BackendReceptionist();
            $receptionist->setBookingId($bookingId)
                ->setUUID($booking->uuid)
                ->setUser($this->user)
                ->setStatus($booking->getStatusText())
                ->setNotes($booking->notes)
                ->setBookingDate($booking->date)
                ->setModifyTime($modifyTime)
                ->setIsRequestedEmployee($booking->isRequestedEmployee)
                ->setConsumer($booking->consumer)
                ->setExtraServiceIds($extraServiceIds)
                ->setIsRequestedEmployee($isRequestedEmployee)
                ->setClientIP(Request::getClientIp())
                ->setSource('backend');

            $booking = $receptionist->upsertBooking();

            //Send calendar invitation to employee
            Event::fire('employee.calendar.invitation.send', [$booking]);

            $data['success']     = true;
            $data['baseURl']     = route('as.index');
            $data['bookingDate'] = $booking->date;
        } catch (\Exception $ex) {
            $receptionist->rollBack();
            $data['success'] = false;
            $data['message'] = ($ex instanceof \Watson\Validating\ValidationException)
                ? Util::getHtmlListError($ex)
                : $ex->getMessage();
        }

        return $data;
    }

    public function changeStatus()
    {
        $bookingId   = Input::get('booking_id');
        $statusText  = Input::get('booking_status');
        $cutId       = Session::get('cutId', null);
        $data        = [];
        try {
            $booking = Booking::ofCurrentUser()->find($bookingId);
            $status  = $booking->getStatus($statusText);
            $booking->setStatus($statusText);

            if ((int) $status === Booking::STATUS_CANCELLED) {
                $booking->delete_reason = 'Cancelled by admin';
                $booking->save();
                $booking->delete();

                //Preventing user confirm reschedule an deleted booking
                if (!empty($cutId) && ($booking->id == $cutId)) {
                    Session::forget('cutId');
                }

            } else {
                $booking->save();
            }

            $data['success'] = true;
        } catch (\Exception $ex) {
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
        $bookingId           = (int) Input::get('booking_id', 0);//if update old booking
        $employeeId          = (int) Input::get('employee_id');
        $serviceTimeId       = Input::get('service_time', 'default');
        $bookingServiceId    = Input::get('booking_service_id', 0);
        $modifyTime          = Input::get('modify_time', 0);
        $hash                = Input::get('hash');
        $bookingDate         = str_standard_date(Input::get('booking_date'));
        $startTimeStr        = Input::get('start_time');
        $uuid                = Input::get('uuid', '');// from ajax uuid
        $isRequestedEmployee = Input::get('is_requested_employee', false);

        try {
            $receptionist = new BackendReceptionist();
            $receptionist->setBookingId($bookingId)
                ->setUUID($uuid)
                ->setUser($this->user)
                ->setBookingDate($bookingDate)
                ->setStartTime($startTimeStr)
                ->setServiceId($serviceId)
                ->setModifyTime($modifyTime)
                ->setEmployeeId($employeeId)
                ->setServiceTimeId($serviceTimeId)
                ->setBookingServiceId($bookingServiceId)
                ->setIsRequestedEmployee($isRequestedEmployee);

            $receptionist->upsertBookingService();
            $data = $receptionist->getResponseData();

        } catch (\Exception $ex) {
            $data = [];
            $data['success'] = false;
            $data['message'] = $ex->getMessage();

            return Response::json($data, 500);
        }

        return Response::json($data);
    }

    /**
     * Delete selected booking service
     */
    public function deleteBookingService()
    {
        //@TODO check can delete the booking service or not?
        $data = [];
        try {
            if (Request::ajax() === true) {
                $uuid             = Input::get('uuid', 0);
                $startTime        = Input::get('start_time');
                $bookingId        = Input::get('booking_id', 0);
                $bookingServiceId = Input::get('booking_service_id', 0);

                //Prevent user delete all booking service when booking was already placed
                if (!empty($bookingId)) {
                    $countBookingService = BookingService::where('tmp_uuid', $uuid)->whereNull('deleted_at')->count();
                    if ($countBookingService === 1) {
                        throw new \Exception(trans('as.bookings.error.delete_last_booking_service'));
                    }
                }

                $bookingService   = BookingService::find($bookingServiceId);
                $date = $bookingService->date;
                $bookingService->delete();

                $receptionist = new BackendReceptionist();
                $receptionist->setUUID($uuid);
                $receptionist->setBookingDate($date);
                $receptionist->setStartTime($startTime);
                $receptionist->setBookingId($bookingId);
                $receptionist->updateBookingServicesTime();
                $data = $receptionist->getDeleteResponseData();
            }
        } catch (\Exception $ex) {
            $data = ['success' => false, 'message' => $ex->getMessage()];

            return Response::json($data, 500);
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
        $modifyTime          = (int) Input::get('modify_times', 0);
        $notes               = Input::get('booking_notes');
        $isRequestedEmployee = Input::get('is_requested_employee', false);
        $isConfirmationSms   = Input::get('is_confirmation_sms', false);
        $isConfirmationEmail = Input::get('is_confirmation_email', false);
        $isReminderSms       = Input::get('is_reminder_sms', false);
        $isReminderEmail     = Input::get('is_reminder_email', false);
        $reminderSmsBefore   = Input::get('reminder_sms_before');
        $reminderSmsUnit     = Input::get('reminder_sms_time_unit');
        $reminderEmailBefore = Input::get('reminder_email_before');
        $reminderEmailUnit   = Input::get('reminder_email_time_unit');
        $extraServiceIds     = Input::get('extra_services');

        try {

            $consumer = $this->handleConsumer();
            $receptionist = new BackendReceptionist();
            $receptionist->setBookingId($bookingId)
                ->setUUID($uuid)
                ->setUser($this->user)
                ->setStatus($bookingStatus)
                ->setNotes($notes)
                ->setIsRequestedEmployee($isRequestedEmployee)
                ->setConsumer($consumer)
                ->setModifyTime($modifyTime)
                ->setExtraServiceIds($extraServiceIds)
                ->setClientIP(Request::getClientIp())
                ->setIsConfimationSms($isConfirmationSms)
                ->setIsConfirmationEmail($isConfirmationEmail)
                ->setIsReminderSms($isReminderSms)
                ->setISReminderEmail($isReminderEmail)
                ->setReminderSmsUnit($reminderSmsUnit)
                ->setReminderSmsBefore($reminderSmsBefore)
                ->setReminderEmailUnit($reminderEmailUnit)
                ->setReminderEmailBefore($reminderEmailBefore)
                ->setSource('backend');

            $booking = $receptionist->upsertBooking();

            Event::fire('employee.calendar.invitation.send', [$booking]);

            $data['success']     = true;
            $data['baseURl']     = route('as.index');
            $data['bookingDate'] = $booking->date;
        } catch (\Exception $ex) {
            $data['success'] = false;
            $data['message'] = ($ex instanceof \Watson\Validating\ValidationException)
                ? Util::getHtmlListError($ex)
                : $ex->getMessage();
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

        $consumer = Consumer::handleConsumer($data, $this->user);

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
            if (empty($cart->details()->count())) {
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
        $extraServiceId = Input::get('extra_service');
        $bookingId      = Input::get('booking_id');
        $data = [];
        try {
            $booking  = Booking::findOrFail($bookingId);
            $bookingExtraService = BookingExtraService::where('extra_service_id', $extraServiceId)
                ->where('tmp_uuid', $booking->uuid)
                ->firstOrFail();

            if (!empty($bookingExtraService)) {
                $bookingExtraService->delete();
            }

            $receptionist = new BackendReceptionist();
            $receptionist->setUUID($booking->uuid)
                ->setBookingId($booking->id)
                ->setStatus($booking->status)
                ->setUser($this->user)
                ->setBookingDate($booking->date)
                ->setStartTime($booking->startTime->format('H:i'))
                ->setBookingId($bookingId)
                ->setConsumer($booking->consumer)
                ->updateBookingServicesTime();
            $receptionist->upsertBooking();
            $extras = $booking->getAvailableExtraServices();

            $data['success'] = true;
            $data['extras'] = $extras;

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
                    ->where(function ($q) use ($keyword) {
                        $q->where('consumers.first_name', 'like', '%' . $keyword . '%')
                            ->orWhere('consumers.last_name', 'like', '%' . $keyword . '%')
                            ->orWhere('consumers.email', 'like', '%' . $keyword . '%')
                            ->orWhere('consumers.phone', 'like', '%' . $keyword . '%');
                    })
                ->get();
        $data = [];
        foreach ($consumers as $consumer) {
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
