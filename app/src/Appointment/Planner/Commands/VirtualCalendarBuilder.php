<?php namespace App\Appointment\Planner\Commands;

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Carbon\Carbon, VIC, Queue;
use App\Core\Models\Business;
use App\Core\Models\User;

class VirtualCalendarBuilder extends ScheduledCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:build-vic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build virtual calendar for checking last minute discount price';

    /**
     * When a command should run
     *
     * @param  Scheduler                                  $scheduler
     * @return \Indatus\Dispatcher\Scheduling\Schedulable
     */
    public function schedule(Schedulable $scheduler)
    {
        return $scheduler->daily()->hours(2)->minutes(0);
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
            if (empty($business->user->id)) {
                continue;
            }
            VIC::enqueue($business->user, $today);
        }
    }

}
