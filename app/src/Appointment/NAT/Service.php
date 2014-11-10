<?php namespace App\Appointment\NAT;

use App\Appointment\Models\Employee;
use App\Appointment\Models\Booking;
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
     * @param Carbon\Carbon $time
     *
     * @return Illuminate\Support\Collection
     */
    public function next($user, $time, $limit = -1)
    {
        if ($time instanceof \Carbon\Carbon) {
            $time = $time->timestamp;
        }

        $key = $this->key('user', $user->id, 'nat');
        $data = $this->redis->zrangebyscore($key, $time, '+inf', 'withscores');
        $timeline = [];

        // Create the timeline. This is an array with key is the timestamp and
        // value is the list of employee IDs
        foreach ($data as $values) {
            list($member, $score) = $values;
            $employeeId = explode(':', $member)[0];
            $timeline[$score][] = $employeeId;

        }

        // Cut the timeline into limited items, or we'll return the whole one
        $counter = 0;
        $limit = ($limit === -1) ? count($timeline) : $limit;
        $collection = new Collection;
        foreach ($timeline as $score => $ids) {
            if ($counter >= $limit) {
                break;
            }

            if (!empty($ids)) {
                $employeeId = $ids[0];

                $item = new \stdClass;
                $item->employee = Employee::find($employeeId);
                $item->time = Carbon::createFromTimestamp($score);

                $collection->push($item);
                $counter++;
            }
        }

        return $collection;
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

        //----------------------------------------------------------------------
        // Remove booked time
        //----------------------------------------------------------------------
        // We will get all bookings of user in the date and remove the
        // corresponding member in sorted list
        $this->removeBookedTime($user, $date);
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
     * Remove all booked time of the user in the given date
     *
     * @param App\Core\Models\User $user
     * @param Carbon\Carbon $date
     *
     * @return void
     */
    protected function removeBookedTime($user, $date)
    {
        $params = [];
        $params[] = $this->key('user', $user->id, 'nat');
        $bookings = Booking::ofUser($user)
            ->where('date', $date->toDateString())
            ->get();

        foreach ($bookings as $booking) {
            $start = 0;
            while ($start < (int) $booking->total) {
                $time = $booking->start_time->addMinutes($start);
                $params[] = $this->key(
                    $booking->employee_id,
                    $time->timestamp
                );

                $start += 15;
            }
        }

        if (count($params) > 1) {
            call_user_func_array([$this->redis, 'zrem'], $params);
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
