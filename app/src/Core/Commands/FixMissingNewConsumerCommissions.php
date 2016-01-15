<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Core\Models\BusinessCommission;
use App\Appointment\Models\Booking;
use App, Config, Settings;
use DB;

class FixMissingNewConsumerCommissions extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'tobook:resurect-new-consumer-commissions';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Resurect missing new consumer commissions.';

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
		$commissions = BusinessCommission::all();

        foreach ($commissions as $item) {
            if(!empty($item->booking->id)) {
                $item->booking->updateNewConsumerCommision($item->consumer_status);
                print('.');
            }
        }
	}

}
