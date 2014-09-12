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
            ->where('total', '>', 0)
            ->leftJoin('as_booking_services', 'as_booking_services.booking_id', '=', 'as_bookings.id')
            ->leftJoin('as_booking_extra_services', 'as_booking_extra_services.booking_id', '=', 'as_bookings.id')
            ->select(
                'as_bookings.*',
                'as_booking_services.service_id',
                'as_booking_services.service_time_id',
                'as_booking_extra_services.extra_service_id'
            )
            ->get();

        foreach ($all as $item) {
            $total = 0;
            $service = null;

            if ($item->extra_service_id !== null) {
                $service = DB::table('as_extra_services')
                    ->where('id', $item->extra_service_id)
                    ->first();
                $total += (int) $service->length;
            }

            if ($item->service_time_id !== null) {
                // Get service time
                $service = DB::table('as_service_times')
                    ->where('id', $item->service_time_id)
                    ->first();

            } elseif ($item->service_id !== null) {
                // Get service
                $service = DB::table('as_services')
                    ->where('id', $item->service_id)
                    ->first();
            }

            if ($service !== null) {
                $total += (int) $service->length;
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
