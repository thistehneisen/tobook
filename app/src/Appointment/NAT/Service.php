<?php namespace App\Appointment\NAT;

use App\Appointment\Models\Employee;
use Illuminate\Support\Collection;
use Redis, Carbon\Carbon, Log;

class Service
{
    protected $redis;

    public function __construct()
    {
        $this->redis = Redis::connection();
    }

    /**
     * Get the next available timeslot of a user
     *
     * @param App\Core\Models\User $user
     *
     * @return Illuminate\Support\Collection
     */
    public function next($user)
    {
        dd($user);
    }

    /**
     * Build the calendar of business users in the given date
     *
     * @param App\Core\Models\User $user
     * @param Carbon\Carbon $date
     *
     * @return void
     */
    public function build($user, $date)
    {
        if ($user->is_business === false) {
            Log::error('Build NAT for non-business user', ['user_id' => $user->id]);
            return;
        }

        // @TODO: Remove members having score of the past

        $employees = Employee::ofUser($user)->with('services')->get();
        foreach ($employees as $employee) {
            $this->pushWorkingTime($user, $employee, $date);
        }
    }

    /**
     * Push the working time of an employee and its timestamp to the sorted
     * set of user
     *
     * @param App\Core\Models\User $user
     * @param App\Appointment\Models\Employee $employee
     * @param Carbon\Carbon $date
     *
     * @return void
     */
    protected function pushWorkingTime($user, $employee, $date)
    {
        $zsetKey = $this->key('user', $user->id, 'nat');
        $params = [];
        $params[] = $zsetKey;

        $defaultEndTime = null;
        $workingTime = $employee->getWorkingTimesByDate($date, $defaultEndTime);
        foreach ($workingTime as $hour => $minutes) {
            foreach ($minutes as $minute) {
                $time = $date->hour($hour)->minute($minute);
                // Here is the score
                $params[] = $time->timestamp;

                // And the key
                $params[] = $this->key(
                    $employee->id,
                    $time->timestamp
                );
            }
        }

        if (count($params) > 1) {
            call_user_func_array([$this->redis, 'zadd'], $params);
        }
    }

    /**
     * Generate key for Redis and Cache
     *
     * @return string
     */
    protected function key()
    {
        return implode(':', func_get_args());
    }
}
