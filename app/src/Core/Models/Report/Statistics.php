<?php namespace App\Core\Models\Report;

use DB;
use Carbon\Carbon;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Employee as EmployeeModel;
use App\Core\Models\User;
use App\Core\Models\Business;

class Statistics
{

	protected $cache;

    /**
     * Start date
     *
     * @var Carbon\Carbon
     */
    protected $start;

    /**
     * End date
     *
     * @var Carbon\Carbon
     */
    protected $end;

    public function __construct(Carbon $start, Carbon $end)
    {
        $this->start        = $start;
        $this->end          = $end;
    }

     /**
     * Return raw data
     *
     * @return array
     */
    public function get()
    {
        return ($this->cache !== null) ? $this->cache : $this->fetch();
    }

    protected function fetch()
    {
        $data = [];
        $ret = [];

        $result = $this->countTotalBookings();
        foreach ($result as $item) {
            $data[$item->user_id]['total'] = $item->total;
        }

        $result = $this->countTotalBookings(true);
        foreach ($result as $item) {
            $status = Booking::getStatusByValue($item->status);
            $data[$item->user_id][$status] = $item->total;
        }

        $result = $this->countTotalBookings(false, true);
        foreach ($result as $item) {
            if ($item->source === 'inhouse' || $item->source === 'cp') {
                $data[$item->user_id]['inhouse'] = $item->total;
            } elseif ($item->source !== 'backend') {
                $data[$item->user_id]['frontend'] = $item->total;
            } elseif($item->source == 'backend') {
            	$data[$item->user_id]['backend'] = $item->total;
            }
        }

        $users = Business::orderBy('name')->join('users', 'users.id', '=', 'businesses.user_id')
            ->whereNull('users.deleted_at')->lists('name', 'user_id');

        foreach ($users as $id => $name) {
            if (isset($data[$id])) {
                $item = $data[$id];
            } else {
                $item = ['total' => 0];
            }

            $item['user_id'] = $name;
            $ret[] = $item;
        }

        $this->cache = $ret;

        return $ret;
    }


    protected function countTotalBookings($groupByStatus = false, $groupBySource = false)
    {
        $count = User::rightJoin('as_bookings', 'users.id', '=', 'as_bookings.user_id')
        	->where('as_bookings.date', '>=', $this->start)
            ->where('as_bookings.date', '<=', $this->end)
            ->whereNull('as_bookings.deleted_at')
            ->whereNull('users.deleted_at');

        if ($groupByStatus) {
            $count = $count->select(
                    DB::raw('COUNT(varaa_as_bookings.id) AS total'),
                    'as_bookings.user_id',
                    'as_bookings.status'
                )
                ->groupBy('user_id', 'status')->get();
        } elseif ($groupBySource && !$groupByStatus) {
            $count = $count->select(
                    DB::raw('COUNT(varaa_as_bookings.id) AS total'),
                    'as_bookings.user_id',
                    'as_bookings.source'
                )
                ->whereNull('as_bookings.deleted_at')
                ->groupBy('user_id', 'source')->get();
        } else {
            $count = $count->select(
                    DB::raw('COUNT(varaa_as_bookings.id) AS total'),
                    'as_bookings.user_id'
                )
                ->groupBy('user_id')->get();
        }

        return $count;
    }

}