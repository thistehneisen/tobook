<?php namespace App\Appointment\Models\NAT;
use Util;
use App\Appointment\Models\Employee;
use App\Appointment\Models\Service;
use App\Appointment\Models\Booking;
use Carbon\Carbon;

/**
 * Providing some essential functionalities for Appointment Scheduler Calendar
 *
 * @author hung@varaa.com
 */
class CalendarKeeper
{
    protected static $instance;

    public static function __callStatic($method, $args)
    {
        if (static::$instance === null) {
            static::$instance = new self();
        }

        return call_user_func_array([static::$instance, $method], $args);
    }

    protected function nextTimeSlots($user, $date = null, $nextHour = null)
    {
        $serviceId = ($user->asOptions->get('default_nat_service') !== -1)
            ? ($user->asOptions->get('default_nat_service'))
            : null;

        $defaultService = (!empty($serviceId))
                ? Service::find($serviceId)
                : null;

        $employees = $this->findEmployees($user, $serviceId);

        $date = (empty($date)) ? Carbon::today() : $date;

        if (!$date instanceof Carbon) {
            try {
                $date = Carbon::createFromFormat('Y-m-d', $date);
            } catch (\Exception $ex) {
                $date = Carbon::today();
            }
        }

        $workingTimes = $this->getDefaultWorkingTimes($user, $date);
        $calendar  = [];
        $services  = [];

        //Simulate the visual calendar into 2-dimensional array
        foreach ($employees as $employee) {
            //Get the shortest service or the given service
            $service = (empty($defaultService->id))
                ? $employee->getRandomActiveService()
                : $defaultService;

            foreach ($workingTimes as $hour => $minutes) {
                foreach ($minutes as $minute) {
                    $calendar[$employee->id][$hour][$minute] =
                        (int) $employee->getSlotClass($date->toDateString(), $hour, $minute, 'next');
                    if (!empty($service)) {
                        $services[$employee->id]['id']      = $service->id;
                        $services[$employee->id]['length']  = $service->length;
                        $services[$employee->id]['price']   = $service->price;
                    }
                }
            }
        }

        $nextAvailable = [];
        $currentEmployeeId = 0;
        $employeesCount = $employees->count();

        foreach ($employees as $employee) {
            //If current employee has no service associate with her jump to next employee
            if (empty($services[$employee->id]['length'])) {
                continue;
            }
            $serviceLength     = $services[$employee->id]['length'];
            $length            = 0;
            $count             = count($nextAvailable);
            foreach ($calendar[$employee->id] as $hour => $minutes) {
                //Skip until current hour is equal nextHour
                if (!empty($nextHour) && $nextHour > $hour) {
                    continue;
                }
                foreach ($minutes as $minute) {
                    $cell = $calendar[$employee->id][$hour][$minute];
                    if ($cell == 15) {
                        $length += $cell;
                        if ($length == $serviceLength) {
                            $time = sprintf('%02d:%02d', $hour, $minute);
                            $nextAvailable[$time] = [
                                'user'     => $user,
                                'employee' => $employee->id,
                                'date'     => $date->toDateString(),
                                'service'  => $services[$employee->id]['id'],
                                'price'    => $services[$employee->id]['price'],
                                'time'     => $time,
                                'hour'     => $hour,
                                'minute'   => $minute
                            ];
                            $length = 0;
                            break;
                        }
                    } else {
                        //reset length
                        $length = 0;
                    }
                }
                /**
                * Change to another employee if has new next available slots has been added.
                * But only apply for those busineses have more than 3 employees
                */
                if (count($nextAvailable) > $count && ($employeesCount > 3)) {
                    break;
                }
            }
        }
        //low to high
        usort($nextAvailable, function ($a, $b) {
            if ($a['hour'] === $b['hour']) {
                return 0;
            }

            return ($a['hour'] < $b['hour']) ? -1 : 1;
        });

        return $nextAvailable;
    }

    /**
     * calculate the default working time for Calendar
     * @param  User     $user
     * @param  Carbon   $date
     * @param  boolean  $showUntilLastestBooking: for BE calendar to show all bookings in range
     * @param  Employee $employee:                if $employee is specified, the min and max value
     *                                            for start and end time will be calculated based on his/her working times
     *                                            otherwise all employees' working times will be taken into account
     * @return Array
     */
    protected function getDefaultWorkingTimes($user, $date, $showUntilLastestBooking = true, $employee = null)
    {
        $workingTimes        = [];
        $settingsWorkingTime = $user->asOptions->get('working_time');
        $currentWeekDay      = Util::getDayOfWeekText($date->dayOfWeek);
        $currentWorkingTimes = $settingsWorkingTime[$currentWeekDay];

        list($startHour, $startMinute) = array_map('intval', explode(':', $currentWorkingTimes['start']));
        list($endHour, $endMinute) = array_map('intval', explode(':', $currentWorkingTimes['end']));

        // get employees' start and end time
        $employees = empty($employee) ? $this->findEmployees($user) : [$employee];
        list($startHour, $startMinute, $endHour, $endMinute) = $this->getMinMaxTime($employees, $date, $startHour, $startMinute, $endHour, $endMinute);

        if ($showUntilLastestBooking) {
            // get the lastest booking end time in current date
            $lastestBooking = Booking::getLastestBookingEndTime($date, $user);
            if (!empty($lastestBooking)) {
                $lastestEndTime = $lastestBooking->getEndAt();
                $latestHour = intval($lastestEndTime->hour);
                $latestMinute = intval($lastestEndTime->minute);

                if (($latestHour >= $endHour) && ($latestMinute > $endMinute)) {
                    $endHour = $latestHour;
                    $endMinute = ($lastestEndTime->minute % 15)
                        ? $latestMinute + (15 - ($latestMinute % 15))
                        : $latestMinute;
                }
            }
        }
        $endHour = ($endMinute === 0) ? $endHour - 1 : $endHour;
        $endMinute = ($endMinute === 0 || $endMinute === 60) ? 45 : $endMinute;

        for ($i = $startHour; $i<= $endHour; $i++) {
            if ($i === $startHour) {
                $workingTimes[$i] = range($startMinute, 45, 15);
            }
            if ($i !== $startHour && $i !== $endHour) {
                $workingTimes[$i] = range(0, 45, 15);
            }
            if ($i === $endHour) {
                $workingTimes[$i] = range(0, $endMinute, 15);
            }
        }

        return $workingTimes;
    }

    private function getMinMaxTime($employees, $date, $minSHour, $minSMin, $maxEHour, $maxEMin)
    {
        foreach ($employees as $employee) {
            list($sHour, $sMin, $eHour, $eMin) = $employee->getStartTimeEndTimeByDate($date);
            if ($sHour === 0 && $sMin === 0) {
                continue;
            }
            if ($sHour < $minSHour) {
                $minSHour = $sHour;
                $minSMin = $sMin;
            } elseif ($sHour === $minSMin && $sMin < $minSMin) {
                $minSMin = $sMin;
            }
            if ($eHour > $maxEHour) {
                $maxEHour = $eHour;
                $maxEMin = $eMin;
            } elseif ($eHour === $maxEHour && $eMin > $maxEMin) {
                $maxEMin = $eMin;
            }
        }

        return [$minSHour, $minSMin, $maxEHour, $maxEMin];
    }

    private function findEmployees($user, $serviceId = null)
    {
        if (!empty($serviceId)) {
            $employees = Employee::where('as_employees.user_id', $user->id)
                ->join('as_employee_service', 'as_employee_service.employee_id', '=', 'as_employees.id')
                ->join('as_services', 'as_employee_service.service_id', '=', 'as_services.id')
                ->where('as_employees.is_active', true)
                ->where('as_services.id', $serviceId)
                ->select('as_employees.*')
                ->get();
        } else {
            $employees = Employee::where('user_id', $user->id)
                ->where('is_active', true)
                ->get();
        }

        return $employees;
    }
}
