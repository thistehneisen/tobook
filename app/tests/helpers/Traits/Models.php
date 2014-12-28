<?php
namespace Test\Traits;

use App\Appointment\Models\CustomTime;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Employee;
use App\Appointment\Models\EmployeeCustomTime;
use App\Appointment\Models\EmployeeDefaultTime;
use App\Appointment\Models\EmployeeFreetime;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\Resource;
use App\Appointment\Models\ResourceService;
use App\Appointment\Models\Room;
use App\Appointment\Models\RoomService;
use App\Appointment\Models\ServiceCategory;
use App\Consumers\Models\EmailTemplate;
use App\Appointment\Models\Consumer;
use App\Consumers\Models\Group;
use App\Consumers\Models\SmsTemplate;
use App\Appointment\Models\Reception\FrontendReceptionist;
use App\Core\Models\Business;
use App\Core\Models\Role;
use App\Core\Models\User;
use Carbon\Carbon;

trait Models
{
    protected $user = null;
    protected $employees = array();

    protected $employee = null;
    protected $category = null;
    protected $customTime = null;

    protected $extraServices = array();

    /**
     * @var Service
     */
    protected $service  = null;

    protected function _createUser($withBusiness = true)
    {
        if (!empty($this->user)) {
            return;
        }

        $this->user = new User([
            'email' => 'user_' . time() . '@varaa.com',
        ]);
        $this->user->password = 123456;
        $this->user->password_confirmation = 123456;
        $this->user->confirmed = 1;
        $this->user->save();
        $this->user->attachRole(Role::user());

        if ($withBusiness) {
            $business = new Business([
                'name'             => 'Business Name',
                'address'          => 'An Address',
                'city'             => 'Some City',
                'postcode'         => 10000,
                'country'          => 'Finland',
                'phone'            => '1234567890',
                'meta_title'       => 'dummy meta_title',
                'meta_keywords'    => 'dummy meta_keywords',
                'meta_description' => 'dummy meta_description',
            ]);
            $business->size = 1;
            $business->is_activated = 1;
            $business->user()->associate($this->user);
            $business->save();
        }
    }

    protected function _createEmployee($employeeCount = 1)
    {
        if (empty($this->user)) {
            $this->_createUser(true);
        }

        for ($i = 0; $i < $employeeCount; $i++) {
            if (!empty($this->employees[$i])) {
                continue;
            }

            $this->employees[$i] = new Employee([
                'name' => 'Employee ' . $i,
                'email' => 'employee_' . $i . $this->user->id . '@varaa.com',
                'phone' => '1234567890',
                'is_active' => 1,
            ]);
            $this->employees[$i]->user()->associate($this->user);
            $this->employees[$i]->saveOrFail();
        }
    }

    protected function _createDefaultTimes(Employee $employee)
    {
        $defaultTimes = [];

        foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $i => $defaultTimeType) {
            $defaultTime = new EmployeeDefaultTime([
                'type' => $defaultTimeType,
                'start_at' => '00:00:00',
                'end_at' => sprintf('%02d:00:00', $i),
                'is_day_off' => ($defaultTimeType == 'fri' ? 1 : 0),
            ]);
            $defaultTime->employee()->associate($employee);
            $defaultTime->save();

            $defaultTimes[] = $defaultTime;
        }

        return $defaultTimes;
    }

    protected function _createEmployeeCustomTimes(Employee $employee)
    {
        if (empty($this->user)) {
            $this->_createUser(true);
        }

        $employeeCustomTimes = [];
        $today = Carbon::today();

        for ($i = 0; $i < 7; $i++) {
            $dayObj = with(clone $today)->addDays($i);

            if ($dayObj->dayOfWeek === Carbon::MONDAY) {
                // no custom time for Monday
                continue;
            }

            $customTime = new CustomTime([
                'name' => 'Custom Time ' . $i,
                'start_at' => '00:00:00',
                'end_at' => sprintf('%02d:00:00', $i),
                'is_day_off' => ($i == 5 ? 1 : 0),
            ]);
            $customTime->user()->associate($this->user);
            $customTime->saveOrFail();

            $employeeCustomTime = new EmployeeCustomTime([
                'date' => $dayObj->toDateString(),
            ]);
            $employeeCustomTime->employee()->associate($employee);
            $employeeCustomTime->customTime()->associate($customTime);
            $employeeCustomTime->save();

            $employeeCustomTimes[] = $employeeCustomTime;
        }

        return $employeeCustomTimes;
    }

    protected function _createEmployeeFreetimes(Employee $employee)
    {
        if (empty($this->user)) {
            $this->_createUser(true);
        }

        $employeeFreetimes = [];
        $today = Carbon::today();

        for ($i = 0; $i < 7; $i++) {
            $dayObj = with(clone $today)->addDays($i);

            if ($dayObj->dayOfWeek === Carbon::TUESDAY) {
                // no free time for Tuesday
                continue;
            }

            $employeeFreetime = new EmployeeFreetime([
                'date' => $dayObj->toDateString(),
                'start_at' => '12:00:00',
                'end_at' => with(clone $dayObj)->hour(13)->addMinutes(15 * $i)->toTimeString(),
            ]);
            $employeeFreetime->user()->associate($this->user);
            $employeeFreetime->employee()->associate($employee);
            $employeeFreetime->saveOrFail();

            $employeeFreetimes[] = $employeeFreetime;
        }

        return $employeeFreetimes;
    }

    protected function _createCategoryServiceAndExtra($categoryCount = 1, $serviceCount = 2, $extraServiceCount = 0, Employee $employee = null)
    {
        static $categoryCreated = 0;
        static $serviceCreated = 0;
        static $extraServiceCreated = 0;

        if (empty($this->user)) {
            $this->_createUser(true);
        }

        if (empty($employee) && empty($this->employees)) {
            $this->_createEmployee();
        }

        $categories = [];

        for ($i = 0; $i < $categoryCount; $i++) {
            $category = new ServiceCategory([
                'name' => 'Category ' . (++$categoryCreated),
                'is_show_front' => 1,
            ]);
            $category->user()->associate($this->user);
            $category->saveOrFail();
            $categories[] = $category;

            for ($j = 0; $j < $serviceCount; $j++) {
                $service = new Service([
                    'name' => 'Service ' . (++$serviceCreated),
                    'during' => 15 * (int) $serviceCreated,
                    'price' => 10 * $serviceCreated,
                    'is_active' => 1,
                ]);
                $service->setLength();
                $service->user()->associate($this->user);
                $service->category()->associate($category);
                $service->saveOrFail();

                if ($employee !== null) {
                    // attach with one employee
                    $service->employees()->attach($employee);
                } else {
                    // attach with all created employee
                    foreach ($this->employees as $employee) {
                        $service->employees()->attach($employee);
                    }
                }

                for ($k = 0; $k < $extraServiceCount; $k++) {
                    $extraService = new ExtraService([
                        'name' => 'Extra Service ' . (++$extraServiceCreated),
                        'price' => 10,
                        'length' => 15,
                    ]);
                    $extraService->user()->associate($this->user);
                    $extraService->saveOrFail();
                    $service->extraServices()->attach($extraService);
                }
            }
        }

        return $categories;
    }

    protected function _createConsumer(User $user = null)
    {
        static $i = 0;

        $consumer = new Consumer([
            'first_name' => sprintf('First_%d %d', $i, time()),
            'last_name' => 'Last',
            'email' => sprintf('consumer_%d_%d@varaa.com', $i, time()),
            'phone' => time() . $i,
            'hash' => '',
        ]);
        $consumer->saveOrFail();

        if ($user != null) {
            $user->consumers()->attach($consumer->id);
        }

        $i++;

        return $consumer;
    }

    protected function _createConsumerGroup(User $user, $consumersCount = 2)
    {
        static $i = 0;

        $group = new Group([
            'name' => sprintf('Consumer Group %d', $i),
        ]);
        $group->user()->associate($user);
        $group->saveOrFail();

        for ($j = 0; $j < $consumersCount; $j++) {
            $consumer = $this->_createConsumer($user);
            $group->consumers()->attach($consumer->id);
        }

        $i++;

        return $group;
    }

    protected function _createEmailTemplate(User $user)
    {
        $campaign = new EmailTemplate([
            'subject' => 'Campaign Subject',
            'content' => 'Campaign Content',
            'from_email' => 'campaign@varaa.com',
            'from_name' => 'Varaa',
        ]);
        $campaign->user()->associate($user);
        $campaign->saveOrFail();

        return $campaign;
    }

    protected function _createSms(User $user)
    {
        $sms = new SmsTemplate([
            'title' => 'SMS Title',
            'content' => 'SMS Content',
            'from_name' => 'SMSSender',
        ]);
        $sms->user()->associate($user);
        $sms->saveOrFail();

        return $sms;
    }

    protected function _modelsReset()
    {
        // should be the first thing to be called in _before() method
        $this->user = null;
        $this->employees = array();

        // hacky way to workaround https://github.com/laravel/framework/issues/1181
        User::boot();
    }

    public function initData($initExtraServices = true, $initResource = false, $initRoom = false)
    {
        $this->user = User::find(70);
        Employee::where('id', 63)->forceDelete();
        $this->employee = new Employee([
            'name' => 'Anne K',
            'email' => 'annek@varaa.com',
            'phone' => '1234567890',
            'is_active' => 1,
        ]);
        $this->employee->id = 63;
        $this->employee->user()->associate($this->user);
        $this->employee->saveOrFail();

        Employee::where('id', 64)->forceDelete();
        $employee1 = new Employee([
            'name' => 'Hung',
            'email' => 'hung@varaa.com',
            'phone' => '1234567890',
            'is_active' => 1,
        ]);
        $employee1->id = 64;
        $employee1->user()->associate($this->user);
        $employee1->saveOrFail();

        ServiceCategory::where('id', 106)->forceDelete();
        $this->category = new ServiceCategory([
            'name' => 'Hiusjuuritutkimus',
            'is_show_front' => 1,
        ]);
        $this->category->id = 106;
        $this->category->user()->associate($this->user);
        $this->category->saveOrFail();

        $this->service = new Service([
            'name'      => 'Hiusjuuritutkimus',
            'length'    => 60,
            'during'    => 45,
            'after'     => 15,
            'price'     => 35,
            'is_active' => 1,
        ]);

        $this->service->user()->associate($this->user);
        $this->service->category()->associate($this->category);
        $this->service->saveOrFail();

        $this->service->employees()->attach($this->employee);
        $this->service->employees()->attach($employee1);

        $serviceTime = new ServiceTime;

        $serviceTime->fill([
            'price'       => 30,
            'before'      => 0,
            'during'      => 60,
            'after'       => 0,
            'description' => '',
        ]);
        ServiceTime::where('id', 1047)->forceDelete();
        $serviceTime->id = 1047;
        $serviceTime->setLength();
        $serviceTime->service()->associate($this->service);
        $serviceTime->save();

        if($initResource) {
            $resource = new Resource;
            $resource->name = 'Resource 1';
            $resource->quantity = 1;
            $resource->description = 'Description';
            $resource->user()->associate($this->user);
            $resource->save();

            $resourceService = new ResourceService;
            $resourceService->service()->associate($this->service);
            $resourceService->resource()->associate($resource);
            $resourceService->save();
        }

        if($initRoom) {
            Room::where('id', 1)->forceDelete();
            $room = new Room;
            $room->id   = 1;
            $room->name = 'room 1';
            $room->description = 'Description';
            $room->user()->associate($this->user);
            $room->save();

            $roomService = new RoomService;
            $roomService->service()->associate($this->service);
            $roomService->room()->associate($room);
            $roomService->save();

            // Room::where('id', 2)->forceDelete();
            // $room1 = new Room;
            // $room1->id   = 2;
            // $room1->name = 'room 2';
            // $room1->description = 'Description';
            // $room1->user()->associate($this->user);
            // $room1->save();

            // $roomService = new RoomService;
            // $roomService->service()->associate($this->service);
            // $roomService->room()->associate($room1);
            // $roomService->save();
        }

        if($initExtraServices) {
            $extraService1 = new ExtraService([
                'name' => 'Extra service 1',
                'price' => 10,
                'length' => 15,
            ]);
            $extraService1->user()->associate($this->user);
            $extraService1->save();

            $extraService2 = new ExtraService([
                'name' => 'Extra service 2',
                'price' => 10,
                'length' => 15,
            ]);
            $extraService2->user()->associate($this->user);
            $extraService2->save();

            $this->extraServices[] = $extraService1;
            $this->extraServices[] = $extraService2;

            $this->service->extraServices()->attach($extraService1);
            $this->service->extraServices()->attach($extraService2);
        }
    }

    public function initCustomTime()
    {
        $date = $this->getDate();

        if (empty($this->user)) {
            $this->user = User::find(70);
        }

        if (empty($this->employee)) {
            $this->initData();
        }

        //Init custome time
        $this->customTime = new CustomTime();

        $this->customTime->fill([
                'name'       => '9:00 to 17:00',
                'start_at'   => '09:00',
                'end_at'     => '17:00',
                'is_day_off' => false
            ]);
        $this->customTime->user()->associate($this->user);
        $this->customTime->save();

        //Add employee custom time

        $employeeCustomTime =  new EmployeeCustomTime;
        $employeeCustomTime->fill([
            'date' =>  $date->toDateString()
        ]);
        $employeeCustomTime->employee()->associate($this->employee);
        $employeeCustomTime->customTime()->associate($this->customTime);
        $employeeCustomTime->save();

        //Add employee freetime
        $employeeFreetime = new EmployeeFreetime();
        $employeeFreetime->fill([
            'date' => $date->toDateString(),
            'start_at' => '13:00',
            'end_at'=>'14:00',
            'description' => ''
        ]);
        $employeeFreetime->user()->associate($this->user);
        $employeeFreetime->employee()->associate($this->employee);
        $employeeFreetime->save();

        // $employeeCustomTime1 =  new EmployeeCustomTime;
        // $employeeCustomTime1->fill([
        //     'date' => $date->toDateString()
        // ]);
        // $employeeCustomTime1->employee()->associate($this->employee);
        // $employeeCustomTime1->customTime()->associate($this->customTime);
        // $employeeCustomTime1->save();

        //Add employee freetime
        // $employeeFreetime1 = new EmployeeFreetime();
        // $employeeFreetime1->fill([
        //     'date' => $date->toDateString(),
        //     'start_at' => '13:00',
        //     'end_at'=>'14:00',
        //     'description' => ''
        // ]);
        // $employeeFreetime1->user()->associate($this->user);
        // $employeeFreetime1->employee()->associate($this->employee);
        // $employeeFreetime1->save();
    }

    public function addMoreRoom($user, $service)
    {
        $room1 = new Room;
        $room1->name = 'room';
        $room1->description = 'Description';
        $room1->user()->associate($user);
        $room1->save();

        $roomService = new RoomService;
        $roomService->service()->associate($service);
        $roomService->room()->associate($room1);
        $roomService->save();
    }

    public function makeBooking($date, $startTime, $service, $employee)
    {
        $user      = User::find(70);
        $employee  = $this->employee;
        $service   = $this->service;
        $uuid      = Booking::uuid();

        $receptionist = new FrontendReceptionist();
        $receptionist->setBookingId(null)
            ->setUUID($uuid)
            ->setUser($user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($startTime)
            ->setServiceId($service->id)
            ->setEmployeeId($employee->id)
            ->setServiceTimeId('default')
            ->setModifyTime(0);

        $bookingService = $receptionist->upsertBookingService();

        $consumer = Consumer::handleConsumer([
            'first_name' => 'Consumer First',
            'last_name' => 'Last ' . $this->service->id,
            'email' => 'consumer_' . $this->service->id . '@varaa.com',
            'phone' => '1234567890',
            'hash' => '',
        ], $user);

        $receptionist = new FrontendReceptionist();
        $receptionist->setBookingId(null)
            ->setUUID($uuid)
            ->setUser($user)
            ->setNotes('notes')
            ->setIsRequestedEmployee(true)
            ->setConsumer($consumer)
            ->setClientIP('192.168.1.1')
            ->setSource('inhouse');

        $booking = $receptionist->upsertBooking();

        return $booking;
    }

    public function getDate()
    {
        $date = Carbon::today()->addDays(1);
        if($date->dayOfWeek == Carbon::SUNDAY) {
            $date->addDays(1);
        } else if($date->dayOfWeek == Carbon::SATURDAY) {
            $date->addDays(2);
        }
        return $date;
    }
}
