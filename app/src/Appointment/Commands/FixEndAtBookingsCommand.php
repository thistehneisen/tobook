<?php namespace App\Appointment\Commands;

use DB, Carbon\Carbon, Closure;
use Illuminate\Console\Command;

class FixEndAtBookingsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:as-fix-end-at';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate end_at value of all bookings';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $sql = "UPDATE varaa_as_bookings SET end_at = ADDTIME(start_at, CONCAT(FLOOR(total / 60), ':', total % 60)) where total > 0;";
        DB::statement($sql);
        $this->info('Update all records with total > 0');

        $all = DB::table('as_bookings')
            ->where('end_at', '00:00:00')
            ->orderBy('id', 'desc')
            ->get();

        foreach ($all as $item) {
            $total = 0;
            $service = null;

            // Get all extra services
            $extraServices = DB::table('as_booking_extra_services')
                ->join('as_extra_services', 'as_booking_extra_services.extra_service_id', '=', 'as_extra_services.id')
                ->where('booking_id', $item->id)
                ->get();

            if (!empty($extraServices)) {
                foreach ($extraServices as $service) {
                    $total += (int) $service->length;
                }
            }

            $services = DB::table('as_booking_services')
                ->join('as_services', 'as_booking_services.service_id', '=', 'as_services.id')
                ->where('booking_id', $item->id)
                ->get();

            if (!empty($services)) {
                foreach ($services as $service)  {
                    if ($service->service_time_id !== null) {
                        // Get service time
                        $service = DB::table('as_service_times')
                            ->where('id', $service->service_time_id)
                            ->first();

                    } elseif ($service->service_id !== null) {
                        // Get service
                        $service = DB::table('as_services')
                            ->where('id', $service->service_id)
                            ->first();
                    }

                    if ($service !== null) {
                        $total += (int) $service->length;
                    }
                }
            }

            $total += (int) $item->modify_time;

            // Add modify time
            $endAt = new Carbon($item->start_at);
            if ($total > 0) {
                $endAt->addMinutes($total);

                DB::table('as_bookings')->where('id', $item->id)
                    ->update(['end_at' => $endAt, 'total' => $total]);
                echo '.';
            }

        }

        $this->info('Done');
    }
}
