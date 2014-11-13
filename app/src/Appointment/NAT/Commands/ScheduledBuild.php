<?php namespace App\Appointment\NAT\Commands;

use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\Cron\Scheduler;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Carbon\Carbon, NAT, Queue;
use App\Core\Models\Business;

class ScheduledBuild extends ScheduledCommand {

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
     * @param Scheduler $scheduler
     * @return \Indatus\Dispatcher\Scheduling\Schedulable
     */
    public function schedule(Schedulable $scheduler)
    {
        // Because we calculate NAT of 4 days in advance
        return $scheduler->daysOfTheWeek([
            Scheduler::MONDAY,
            Scheduler::FRIDAY
        ]);
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
        $i = 0;
        while ($i < 4) {
            foreach ($businesses as $business) {
                // Push user and the date into queue to build NAT calendar
                NAT::enqueueToBuild($business->user, $today);
            }

            $today = $today->addDays($i);
            $i++;
        }
    }

}
