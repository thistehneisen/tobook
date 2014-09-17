<?php namespace App\Appointment\Commands;
use DB, Carbon\Carbon, Closure;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FixBookingTotalCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:as-fix-booking-total';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate total from as_bookings_services to varaa_as_bookings.';

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
        $this->info('Update varaa_as_bookings.total from as_bookings_services.total');
        $sql = <<<SQL
UPDATE varaa_as_bookings SET varaa_as_bookings.total =
(
    SELECT
        t2.total
    FROM as_bookings t1
    JOIN as_bookings_services t2
        ON t1.id = t2.booking_id
    WHERE t1.uuid = varaa_as_bookings.uuid COLLATE utf8_unicode_ci LIMIT 1
);
SQL;
        DB::statement($sql);
        $this->info('Done update varaa_as_bookings.total');

        //update bookings.end_at base on total
        $this->updateBookingEndAt();
    }

    private function updateBookingEndAt()
    {
        $sql = "UPDATE varaa_as_bookings SET end_at = ADDTIME(start_at, CONCAT(FLOOR(total / 60), ':', total % 60)) where total > 0;";
        DB::statement($sql);
        $this->info('Update all records with total > 0');
    }
}
