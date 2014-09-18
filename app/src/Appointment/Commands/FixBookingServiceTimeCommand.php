<?php namespace App\Appointment\Commands;
use DB, Carbon\Carbon, Closure;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FixBookingServiceTimeCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:fix-booking-service-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-determine service time of a booking';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $all = DB::table('as_bookings')
            ->select(
                'as_bookings.*',
                'as_services.length',
                'as_booking_services.service_id',
                'as_booking_services.employee_id',
                'as_booking_services.service_time_id'
            )
            ->join('as_booking_services', 'as_bookings.id', '=', 'as_booking_services.booking_id')
            ->join('as_services', 'as_booking_services.service_id','=','as_services.id')
            ->where('as_services.length', '!=', DB::raw('varaa_as_bookings.total'))
            ->whereNull('as_booking_services.service_time_id')
            ->orderBy('as_bookings.id', 'desc')
            ->get();

        $count                 = 0;
        $countUpdate           = 0;
        $countPlusTime         = 0;
        $countFutureTotal      = 0;
        $countExtraService     = 0;
        $countFutureUpdated    = 0;
        $countUpdateModifyTime = 0;
        foreach ($all as $item) {
            if ($item->date > '2014-09-17') {
                $countFutureTotal += 1;
            }

            $count += 1;
            $rawTotal = $item->total;
            if ($rawTotal == $item->length && empty($item->service_time_id)) {
                continue;
            }

            $employeeService = DB::table('as_employee_service')
                ->where('service_id', $item->service_id)
                ->where('employee_id', $item->employee_id)->first();

            if (!empty($employeeService)) {
                if ((int) $employeeService->plustime > 0) {
                    $countPlusTime += 1;
                }
                $rawTotal -= (int) $employeeService->plustime;
            }

            $extraServices = DB::table('as_booking_extra_services')
            ->join('as_extra_services', 'as_booking_extra_services.extra_service_id', '=', 'as_extra_services.id')
            ->where('booking_id', $item->id)
            ->get();

            if (!empty($extraServices)) {
                $countExtraService += 1;
                foreach ($extraServices as $service) {
                    $rawTotal -= (int) $service->length;
                }
            }

            $serviceTime = DB::table('as_service_times')
                ->where('service_id', $item->service_id)
                ->where('length', $rawTotal)->first();

            if (!empty($serviceTime)) {
                DB::table('as_booking_services')->where('booking_id', $item->id)
                    ->update(['service_time_id' => $serviceTime->id]);
                echo '.';
                $countUpdate += 1;
                if ($item->date > '2014-09-17') {
                    $countFutureUpdated += 1;
                }
            } else {
                DB::table('as_bookings')->where('id', $item->id)
                    ->update(['modify_time'=> (int)($item->total - $item->length)]);
                $countUpdateModifyTime++;
            }
        }
        $this->info(sprintf('%d rows', $count));
        $this->info(sprintf('%d are updated', $countUpdate));
        $this->info(sprintf('%d have extra services', $countExtraService));
        $this->info(sprintf('%d have plus time', $countPlusTime));
        $this->info(sprintf('%d in the future', $countFutureTotal));
        $this->info(sprintf('%d in the future updated', $countFutureUpdated));
        $this->info(sprintf('%d has been updated modify time', $countUpdateModifyTime));
    }
}