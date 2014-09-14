<?php namespace App\Appointment\Commands;
use Illuminate\Console\Command;
use DB, Carbon\Carbon, Closure;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FixBookingStatusCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:as-fix-booking-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate status from as_bookings_status to varaa_as_bookings';

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
        $this->info('Update booking status');
        DB::setTablePrefix('');

        $map = [
            'confirmed'     => 1,
            'pending'       => 2,
            'cancelled'     => 3,
            'arrived'       => 4,
            'paid'          => 5,
            'no_show_up'    => 6,
        ];

        $booking_statuses = DB::table('as_bookings_status')
            ->join('as_bookings', 'as_bookings.id', '=', 'as_bookings_status.booking_id')
            ->orderBy('as_bookings.id', 'desc')
            ->get();

        foreach ($booking_statuses as $item) {
            $status = isset($map[$item->status]) ? $map[$item->status] : 1;
            DB::table('varaa_as_bookings')->where('uuid', $item->uuid)
                    ->update(['status' => $status]);
        }

         $this->info('Done');
    }
}
