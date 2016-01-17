<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Core\Models\BusinessCommission;
use App\Consumers\Models\Consumer;
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
		$commissions = BusinessCommission::withTrashed()->orderBy('booking_id')->get();

        foreach ($commissions as $item) {
            if(!empty($item->booking->id)) {
                $item->booking->updateNewConsumerCommision($item->consumer_status);
                print('.');
            } else {
            	$this->updateNewConsumerCommision($item);
            	print('_');
            }
        }
	}

	private function updateNewConsumerCommision($item)
    {
        if (!is_tobook()) {
            return;
        }

        $isNew = ($item->consumer_status == 'new') ? true : false;
        $booking = Booking::withTrashed()->find($item->booking_id);

        $consumerStatus        = $isNew ? Consumer::STATUS_NEW : Consumer::STATUS_EXIST;
        $newConsumerRate       = Settings::get('new_consumer_commission_rate');
        $constantCommission    = Settings::get('constant_commission');
        $newConsumerCommission = ($isNew) ? ($newConsumerRate * $booking->total_price) : 0;

        if (!empty($item->id)) {
            $item->consumer_status = $consumerStatus;
            if ($isNew) {
                $item->new_consumer_commission = $newConsumerCommission;
            } else {
                $item->new_consumer_commission = 0;
            }
            return $item->save();
        }
    }

}
