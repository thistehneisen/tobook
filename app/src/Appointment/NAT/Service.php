<?php namespace App\Appointment\NAT;

use App\Core\Models\User;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Booking;
use Illuminate\Support\Collection;
use Redis, Carbon\Carbon, Log, Queue;

class Service
{
    const QUEUE = 'varaa:nat';
    protected $redis;

    public function __construct()
    {
        // Assign Redis connection as attribute for quickly access
        $this->redis = Redis::connection();
    }

    /**
     * Enqueue to build NAT for business user in the given date
     *
     * @param App\Core\Models\User $user
     * @param Carbon\Carbon $date
     *
     * @return void
     */
    public function enqueueToBuild($user, $date)
    {
        // Push this job into the special queue called 'varaa:nat'
        Queue::push('App\Appointment\NAT\Service@scheduledBuild', [
            'date'   => $date->toDateTimeString(),
            'userId' => $user->id,
        ], static::QUEUE);
    }

    /**
     * This method will be run in queue to build NAT of a user
     *
     * @param  $job
     * @param array $data
     *
     * @return void
     */
    public function scheduledBuild($job, $data)
    {
        Log::info('Start to build NAT', $data);

        $user = User::find($data['userId']);
        $date = new Carbon($data['date']);

        if ($user !== null) {
            $this->build($user, $date);
        }

        Log::info('Finish building NAT', $data);
        // Done
        $job->delete();
    }

    /**
     * Get the next available timeslot of a user
     *
     * @param App\Core\Models\User $user
     * @param Carbon\Carbon        $time
     * @param int                  $limit
     *
     * @return Illuminate\Support\Collection
     */
    public function nextUser($user, $time, $limit = -1)
    {
        Log::info('Get NAT', ['user' => $user->id, 'time' => $time->toDateTimeString()]);

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
                // Pick randomly from the list of available employees
                $employeeId = $ids[array_rand($ids)];
                $date = Carbon::createFromTimestamp($score);

                // Pick a random service
                $employee = Employee::find($employeeId);
                $service = $employee->services()
                    ->where('is_active', true)
                    ->get()
                    ->random();

                $item = [];
                $item['employee'] = $employeeId;
                $item['date']     = $date->toDateString();
                $item['time']     = $date->toTimeString();
                $item['hour']     = $date->hour;
                $item['minute']   = $date->minute;
                $item['service']  = $service->id;
                $item['price']    = $service->price;

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

        $key = $this->key('user', $user->id, 'nat');
        // Since we don't use members whose score is in the past, we should
        // remove them
        $this->removePastValues($key, $date);

        //----------------------------------------------------------------------
        // Build working time of all employees
        //----------------------------------------------------------------------
        // We'll first build the ideal working time of an employee. This
        // calendar takes default working time of business, default working
        // time of employee, custom working time and free time into account.
        $employees = Employee::ofUser($user)->get();
        foreach ($employees as $employee) {
            $this->pushWorkingTime($key, $employee, $date);
        }

        //----------------------------------------------------------------------
        // Remove booked time
        //----------------------------------------------------------------------
        // Get all bookings of user in the date and remove the corresponding
        // member in sorted list
        $this->removeBookedTime($key, $user, $date);
    }

    /**
     * Remove all members of sorted set that have scores less than the given
     * date
     *
     * @param string $key The key of sorted set
     * @param Carbon\Carbon $date
     *
     * @return void
     */
    protected function removePastValues($key, $date)
    {
        $this->redis->zremrangebyscore($key, '-inf', $date->timestamp);
    }

    /**
     * Push the working time of an employee and its timestamp to the sorted
     * set of user
     *
     * @param string $key Key of sorted set
     * @param App\Appointment\Models\Employee $employee
     * @param Carbon\Carbon $date
     *
     * @return void
     */
    protected function pushWorkingTime($key, $employee, $date)
    {
        $params = [];
        $params[] = $key;

        $_ = null;
        $workingTime = $employee->getWorkingTimesByDate($date, $_);
        foreach ($workingTime as $hour => $minutes) {
            foreach ($minutes as $minute) {
                $time = $date->hour($hour)->minute($minute);
                // We'll use timestamp as score. This could help to get members
                // chronically
                $params[] = $time->timestamp;

                // The member name is followed this format `$userId:$timestamp`
                // Because if an employee could be available at many specific
                // time and sorted set doesn't allow a member to have many
                // scores.
                $params[] = $this->key($employee->id, $time->timestamp);
            }
        }

        if (count($params) > 1) {
            call_user_func_array([$this->redis, 'zadd'], $params);
        }
    }

    /**
     * Remove all booked time of the user in the given date
     *
     * @param string $key
     * @param App\Core\Models\User $user
     * @param Carbon\Carbon $date
     *
     * @return void
     */
    protected function removeBookedTime($key, $user, $date)
    {
        $params = [];
        $params[] = $key;
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

                // @TODO: Magic number
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
