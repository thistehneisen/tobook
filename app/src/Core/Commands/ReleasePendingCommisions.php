<?php namespace App\Core\Commands;

use App;
use App\Core\Models\BusinessCommission;
use Carbon\Carbon;
use Config;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ReleasePendingCommisions extends ScheduledCommand
{
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
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        // Set `release_comission` === false in config/app.php to stop
        // littering laravel.log
        if (Config::get('app.release_comission', true)) {
            $cutoff = Carbon::now()->subMinutes(Config::get('varaa.cart.hold_time'));
            BusinessCommission::releaseCommission($cutoff);
        }
    }
}
