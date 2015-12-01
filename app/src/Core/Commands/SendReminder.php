<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\DateTime\Scheduler;
use App\Appointment\Models\ConfirmationReminder;

class SendReminder extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'varaa:send-reminder';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send sms and reminder out every hour';


	 /**
     * When a command should run
     *
     * @param Scheduler $scheduler
     *
     * @return Scheduler
     */
    public function schedule(Schedulable $scheduler)
    {
        return $scheduler->everyMinutes(15);
    }


	 /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
       ConfirmationReminder::sendReminders();
    }

}
