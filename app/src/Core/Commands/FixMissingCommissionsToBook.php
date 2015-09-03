<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Core\Models\BusinessCommission;
use App\Appointment\Models\Booking;
use App, Config, Settings;
use DB;

class FixMissingCommissionsToBook extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'tobook:resurect-commissions';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Resurect missing commissions';

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
		$result = DB::table('as_bookings')
            ->leftJoin('business_commissions', 'as_bookings.id', '=', 'business_commissions.booking_id')
            ->where('as_bookings.source', '=','inhouse')
            ->whereNull('business_commissions.id')
            ->whereNull('as_bookings.deleted_at')
            ->select('as_bookings.id')
            ->get();

        foreach ($result as $item) {
            $booking = Booking::find($item->id);
            if(!empty($booking)) {
                $booking->saveCommission();
                print('.');
            }
        }
	}
}
