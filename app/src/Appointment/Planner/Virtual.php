<?php namespace App\Appointment\Planner;

use App, View, Confide, Redirect, Input, Config, Util, Response, Request, Validator, DB;
use App\Core\Models\User;
use App\Appointment\Models\NAT\CalendarKeeper;
use App\Appointment\Models\Reception\BackendReceptionist;
use App\Appointment\Models\Employee;
use App\Appointment\Models\CustomTime;
use Carbon\Carbon;
use Redis;
use Queue, Log;

/**
 * Virtual Calendar Planner
 */
class Virtual
{
    public $bookableTimes = [];

    public $timeslots = [];

    public $redis;

    const QUEUE = 'varaa:vc';

    public function __construct()
    {
        // Assign Redis connection as attribute for quickly access
        $this->redis = Redis::connection();
    }

    public function enqueue($user, $date)
    {
        $i = 0;
        while ($i < 2) {
            Log::debug('Queue to build virtual calendar', ['userId' => $user->id, 'date' => $date->toDateString()]);

            // Push this job into the special queue called 'varaa:vc'
            Queue::push('App\Appointment\Planner\Virtual@scheduledBuild', [
                'date'   => $date->toDateString(),
                'userId' => $user->id,
            ], static::QUEUE);

            $date->addDay();
            $i++;
        }
    }

    /**
     * This method will be run in queue to build VC of a user
     *
     * @param  $job
     * @param array $data
     *
     * @return void
     */
    public function scheduledBuild($job, $data)
    {
        Log::info('Start to build VC', $data);

        $user = User::find($data['userId']);
        $date = new Carbon($data['date']);

        if ($user !== null) {
            $this->build($user, $date);
        }

        Log::info('Finish building VC', $data);
        // Done
        $job->delete();
    }

    public function build($user, $date)
    {
        $timeslots = $this->getBookableTimeslots($user, $date);
        $key = $this->getKey($user, $date);

        foreach ($timeslots as $timeslot) {
            $this->addToList($key, $timeslot);
        }
    }

    public function getKey($user, $date)
    {
        return sprintf('user_%s_%s', $user->id, $date->format('dmY'));
    }

    public function getTimeslots($user, $date)
    {
        return $this->redis->lrange($this->getKey($user, $date), 0, -1);
    }

    public function addToList($key, $slot)
    {
        try {
            $this->redis->rpush($key, $slot);
        } catch(\Exception $ex){
            Log::debug('Exception:', [$ex]);
        }
    }

    /**
     * Get all possible bookable timeslots of all employees with their shortest services
     *
     * @return array
     */
    public function getBookableTimeslots($user, $date)
    {
        $this->timeslots = [];

        foreach ($user->asEmployees as $employee) {
            $this->singleEmployeeTimeslots($user, $employee, $date);
        }

        return $this->timeslots;
    }

    /**
     * Go though the employee calendar and calculate the available slots
     *
     * @return array()
     */
    public function singleEmployeeTimeslots($user, $employee, $date)
    {
        $workingTimes = CalendarKeeper::getDefaultWorkingTimes($user, $date, true, $employee);
        $service = $employee->shortestService;

        foreach ($workingTimes as $hour => $minutes){
            foreach ($minutes as $minute){
                $this->singleTimeslot($employee, $service, $date, $hour, $minute);
            }
        }

        return $this->timeslots;
    }

    /**
     * Add the timeslot to the list if it is bookable
     *
     * @return void
     */
    public function singleTimeslot($employee, $service, $date, $hour, $minute)
    {
        if (!$this->isActiveSlot($employee, $date, $hour, $minute)) {
            return;
        }

        if ($date->copy()->hour($hour)->minute($minute) < Carbon::now()) {
            return;
        }

        $time = sprintf("%02d:%02d", $hour, $minute);

        if (!isset($this->bookableTimes[$hour][$minute])
            || $this->bookableTimes[$hour][$minute] === false) {

            // if the shortest service is 15, the slot is bookable anyway
            $this->bookableTimes[$hour][$minute] = (intval($service->length) === 15)
                ? true
                : $this->isBookable($employee, $service, $date, $time);

            if ($this->bookableTimes[$hour][$minute]) {
                $this->timeslots[] = $time;
            }
        }

    }

    /**
     * Check if the timeslot is 'active' or not
     *
     * @return boolean
     */
    public function isActiveSlot($employee, $date, $hour, $minute)
    {
        $unbookables = ['inactive','fancybox','booked', 'freetime', 'room', 'resource', 'head', 'body', '_', ' '];
        $slotClass = $employee->getSlotClass($date->toDateString(), $hour, $minute);
        $slotClass = str_replace($unbookables, '', $slotClass);
        return ($slotClass === 'active');
    }

    /**
     * Check if the slot is bookable with certain employee and service
     *
     * @return boolean
     */
    public function isBookable($employee, $service, $date, $time)
    {
        $receptionist = new BackendReceptionist();
        $receptionist->setUser($service->user)
            ->setBookingDate($date->toDateString())
            ->setStartTime($time)
            ->setServiceId($service->id)
            ->setServiceTimeId('default')
            ->setEmployeeId($employee->id);

        return $receptionist->isBookable();
    }
}
