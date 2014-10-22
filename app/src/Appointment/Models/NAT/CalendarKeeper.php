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

    protected function getDefaultWorkingTimes($user, $date, $showUntilLastestBooking = true)
    {
        $workingTimes        = [];
        $settingsWorkingTime = $user->asOptions->get('working_time');
        $currentWeekDay      = Util::getDayOfWeekText($date->dayOfWeek);
        $currentWorkingTimes = $settingsWorkingTime[$currentWeekDay];

        list($startHour, $startMinute) = explode(':', $currentWorkingTimes['start']);
        list($endHour, $endMinute)     = explode(':', $currentWorkingTimes['end']);

        if ($showUntilLastestBooking) {
            //Get the lastest booking end time in current date
            $lastestBooking = Booking::getLastestBookingEndTime($date, $user);
            if (!empty($lastestBooking)) {
                $lastestEndTime = $lastestBooking->getEndAt();
                if(($lastestEndTime->hour   >= $endHour)
                && ($lastestEndTime->minute >  $endMinute))
                {
                    $endHour   = $lastestEndTime->hour;
                    $endMinute = ($lastestEndTime->minute % 15)
                        ? $lastestEndTime->minute + (15 - ($lastestEndTime->minute % 15))
                        : $lastestEndTime->minute;
                }
            }
        }
        $endHour   = ($endMinute == 0) ? $endHour - 1 : $endHour;
        $endMinute = ($endMinute == 0 || $endMinute == 60) ? 45 : $endMinute;

        for ($i = (int) $startHour; $i<= (int) $endHour; $i++) {
            if ($i === (int) $startHour) {
                $workingTimes[$i] = range((int) $startMinute, 45, 15);
            }
            if ($i !== (int) $startHour && $i !== (int) $endHour) {
                $workingTimes[$i] = range(0, 45, 15);
            }
            if ($i === (int) $endHour) {
                $workingTimes[$i] = range(0, (int) $endMinute, 15);
            }
        }
        return $workingTimes;
    }

    public function findEmployees($user, $serviceId)
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
