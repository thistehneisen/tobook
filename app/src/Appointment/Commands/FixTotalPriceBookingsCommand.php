<?php namespace App\Appointment\Commands;

use DB, Carbon\Carbon, Closure;
use Illuminate\Console\Command;

class FixTotalPriceBookingsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:as-fix-total-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate total_price value of all bookings';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $all = DB::table('as_bookings')
            ->orderBy('id', 'desc')
            ->get();

        foreach ($all as $item) {
            $total = 0.0;

            // Extra services
            $extraServices = DB::table('as_booking_extra_services')
                ->join('as_extra_services', 'as_booking_extra_services.extra_service_id', '=', 'as_extra_services.id')
                ->where('booking_id', $item->id)
                ->get();

            if (!empty($extraServices)) {
                foreach ($extraServices as $service) {
                    $total += (double) $service->price;
                }
            }

            // Services
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
                        $total += (double) $service->price;
                    }
                }
            }

            if ($total > 0) {
                DB::table('as_bookings')->where('id', $item->id)
                    ->update(['total_price' => round($total, 2)]);
                echo '.';
            }
        }

        $this->info('Done');
    }
}
