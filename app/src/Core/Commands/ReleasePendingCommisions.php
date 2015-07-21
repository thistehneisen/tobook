<?php namespace App\Core\Commands;

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Core\Models\BusinessCommission;
use Carbon\Carbon;
use App, Config;

class ReleasePendingCommisions extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'varaa:release-pending-commissions';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Release pending commissions';

    /**
     * When a command should run
     *
     * @param Scheduler $scheduler
     *
     * @return Scheduler
     */
    public function schedule(Schedulable $scheduler)
    {
        return $scheduler;
    }

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
		$cutoff = Carbon::now()->subMinutes(Config::get('varaa.cart.hold_time'));
        BusinessCommission::releaseCommission($cutoff);
	}

}
