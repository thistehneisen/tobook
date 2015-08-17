<?php namespace App\Appointment\NAT\Commands;

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Carbon\Carbon, NAT, Queue;
use App\Core\Models\Business;

class ScheduledBuild extends ScheduledCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:build-nat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build NAT calendar of all business users';

    /**
     * When a command should run
     *
     * @param  Scheduler                                  $scheduler
     * @return \Indatus\Dispatcher\Scheduling\Schedulable
     */
    public function schedule(Schedulable $scheduler)
    {
        // Because we calculate NAT of 4 days in advance, so we need to run this
        // task every 96 hours
        return $scheduler->everyHours(96);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $businesses = Business::with('user')->get();
        $today = Carbon::today();
        foreach ($businesses as $business) {
            // Push user and the date into queue to build NAT calendar
            NAT::enqueueToBuild($business->user, $today->copy());
        }
    }

}
