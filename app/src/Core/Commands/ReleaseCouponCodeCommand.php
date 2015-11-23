<?php namespace App\Core\Commands;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Core\Models\CouponBooking;
use Config;
use Carbon\Carbon;

class ReleaseCouponCodeCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'varaa:release-coupon-code';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Release coupon code if booking is not confirmed';

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
		// Set `release_coupon` === false in config/app.php to stop
        // littering laravel.log
        if (Config::get('app.release_coupon', true)) {
            $cutoff = Carbon::now()->subMinutes(Config::get('varaa.cart.hold_time'));
            CouponBooking::releaseCoupon($cutoff);
        }
	}

}
