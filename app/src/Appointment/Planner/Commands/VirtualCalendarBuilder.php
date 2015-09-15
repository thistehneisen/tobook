<?php namespace App\Appointment\Planner\Commands;

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Carbon\Carbon, NAT, Queue;
use App\Core\Models\Business;
use App\Appointment\Planner\Virtual;
use App\Core\Models\User;

class VirtualCalendarBuilder extends ScheduledCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:build-virtual-calendar';

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
        return $scheduler->everyHours(24);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $businesses = Business::with('user')->get();
        $today    = Carbon::today();
        $virtual   = new Virtual();
        foreach ($businesses as $business) {
            if($business->user->id == 62)
                $virtual->enqueue($business->user, $today);
                // dd($virtual->getTimeslots($business->user, $today));
        }
    }

}
