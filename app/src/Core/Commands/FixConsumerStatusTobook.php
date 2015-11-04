<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use DB;
use Util;
use App\Core\Models\BusinessCommission;
use App\Consumers\Models\Consumer;
use App\Appointment\Models\Booking;

class FixConsumerStatusTobook extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'varaa:fix-consumer-status';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Fix wrong consumer status in commission counter';

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
		$results = DB::table('business_commissions')
			->leftJoin('as_bookings', 'as_bookings.id', '=', 'business_commissions.booking_id')
			->leftJoin('consumers','consumers.id', '=','as_bookings.consumer_id')
			->select('business_commissions.id','as_bookings.created_at', 'as_bookings.consumer_id', DB::raw('varaa_consumers.created_at as consumer_created_at'))
			->get();

		try{
			foreach ($results as $commission) {
				$consumerCreatedAt = Util::formatIgnoreSeconds($commission->consumer_created_at);
				$bookingCreatedAt  = Util::formatIgnoreSeconds($commission->created_at);
				if ($consumerCreatedAt === $bookingCreatedAt) {
					$businessCommission = BusinessCommission::find($commission->id);
					if (!empty($businessCommission)) {
						$businessCommission->consumer_status = Consumer::STATUS_NEW;
						$businessCommission->save();
						print('.');
					}
				}
			}
		}catch(\Exception $ex){
			print($ex->getLine());
			print($ex->getMessage());
		}
	}

}
