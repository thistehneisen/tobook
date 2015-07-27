<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Core\Models\BusinessCommission;
use App\Appointment\Models\Booking;
use App, Config, Settings;

class FixPayAtVenueCommisions extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'varaa:fix-pay-at-venue-commissions';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Remove duplicates and wrong calculation of Pay at venue';

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
		$commissions = BusinessCommission::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();
        $storage = [];
        $deletes = [];
        // Go through all cart details and release them
        foreach ($commissions as $commission) {
            $storage[$commission->booking_id][] = $commission->id;
            if(count($storage[$commission->booking_id]) >=2) {
                $deletes[] = $commission->id;
            }
        }

        foreach ($deletes as $id) {
            $comm =  BusinessCommission::find($id);
            $comm->delete();
            printf("\nDelete %s", $id);
        }

        if (App::environment() === 'tobook') {
            printf("\n Do not run later part on tobook");
            return;
        }

        $comms = BusinessCommission::whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();
        $updates = [];
        //Recalculate commission for
        foreach ($comms as $comm) {
            $commission = BusinessCommission::find($comm->id);
            $commissionRate   = Settings::get('commission_rate');
            $commissionAmount = $comm->total_price * $commissionRate;

            if((int)$commission->booking_status === Booking::STATUS_CONFIRM) {
                $commissionAmount = $commission->total_price + $commissionAmount;
            }
            if($commissionAmount > 0){
                $updates[] = $comm->id;
                $commission->commission = $commissionAmount;
                $commission->save();
                printf("Update commission: user_id %s - id: %s - amount : %s\n", $commission->user_id, $comm->id, $commissionAmount);
            }
        }
	}
}
