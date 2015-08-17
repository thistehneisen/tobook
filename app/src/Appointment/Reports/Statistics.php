<?php namespace App\Appointment\Reports;

use DB;
use Carbon\Carbon;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Employee as EmployeeModel;

class Statistics extends Base
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

    /**
     * If == 0, generate statistics data of this all services
     *
     * @var int
     */
    protected $serviceId;

    public function __construct($serviceId, Carbon $start, Carbon $end)
    {
        $this->start        = $start;
        $this->end          = $end;
        $this->serviceId    = $serviceId;
    }

    public function toJson()
    {
        $data = $this->get();
        $json = [];
        foreach ($data as $item) {
            $json[] = [
                'employee'  => $item['employee'],
                'total'     => isset($item['total']) ? $item['total'] : 0,
                'confirmed' => isset($item['confirmed']) ? $item['confirmed'] : 0,
                'pending'   => isset($item['pending']) ? $item['pending'] : 0,
                'cancelled' => isset($item['cancelled']) ? $item['cancelled'] : 0,
            ];
        }

        return json_encode($json);
    }

    protected function fetch()
    {
        $data = [];
        $ret = [];

        $result = $this->countTotalBookings();
        foreach ($result as $item) {
            $data[$item->employee_id]['total'] = $item->total;
        }

        $result = $this->countTotalBookings(true);
        foreach ($result as $item) {
            $status = Booking::getStatusByValue($item->status);
            $data[$item->employee_id][$status] = $item->total;
        }

        $result = $this->countTotalBookings(false, true);
        foreach ($result as $item) {
            if ($item->source === 'inhouse') {
                $data[$item->employee_id]['inhouse'] = $item->total;
            } elseif ($item->source !== 'backend') {
                $data[$item->employee_id]['frontend'] = $item->total;
            }
        }

        $employees = EmployeeModel::ofCurrentUser()->lists('name', 'id');
        foreach ($employees as $id => $name) {
            if (isset($data[$id])) {
                $item = $data[$id];
            } else {
                $item = ['total' => 0];
            }

            $item['employee'] = $name;
            $ret[] = $item;
        }

        $this->cache = $ret;

        return $ret;
    }

    protected function countTotalBookings($groupByStatus = false, $groupBySource = false)
    {
        $count = Booking::ofCurrentUser()
            ->where('as_bookings.date', '>=', $this->start)
            ->where('as_bookings.date', '<=', $this->end)
            ->whereNull('as_bookings.deleted_at');

        if ($this->serviceId !== 0) {
            $count = $count->join('as_booking_services', function ($join) {
                $join->on('booking_id', '=', 'as_bookings.id')
                    ->where('service_id', '=', $this->serviceId);
            });
        }

        if ($groupByStatus) {
            $count = $count->select(
                    DB::raw('COUNT(varaa_as_bookings.id) AS total'),
                    'as_bookings.employee_id',
                    'as_bookings.status'
                )
                ->groupBy('employee_id', 'status')->get();
        } elseif ($groupBySource && !$groupByStatus) {
            $count = $count->select(
                    DB::raw('COUNT(varaa_as_bookings.id) AS total'),
                    'as_bookings.employee_id',
                    'as_bookings.source'
                )->where('as_bookings.status','!=', Booking::STATUS_CANCELLED)
                ->whereNull('as_bookings.deleted_at')
                ->groupBy('employee_id', 'source')->get();
        } else {
            $count = $count->select(
                    DB::raw('COUNT(varaa_as_bookings.id) AS total'),
                    'as_bookings.employee_id'
                )
                ->groupBy('employee_id')->get();
        }

        return $count;
    }
}
