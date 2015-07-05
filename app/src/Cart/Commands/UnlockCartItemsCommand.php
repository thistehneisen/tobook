<?php namespace App\Cart\Commands;

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Core\Models\BusinessCommission;
use Carbon\Carbon;
use Cart, App, Config;

class UnlockCartItemsCommand extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'varaa:unlock-cart-items';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Remove items from locking after a period of time';

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
	 * When a command should run
	 *
	 * @param Scheduler $scheduler
	 * @return \Indatus\Dispatcher\Scheduling\Schedulable
	 */
	public function schedule(Schedulable $scheduler)
	{
		return $scheduler;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        // Sorry but because my computer is slow so I need to disable it
        if (App::environment() !== 'local') {
            $cutoff = Carbon::now()->subMinutes(Config::get('varaa.cart.hold_time'));
            Cart::scheduledUnlock($cutoff);
            BusinessCommission::releaseCommission($cutoff);
        }
	}

}
