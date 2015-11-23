<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\DateTime\Scheduler;
use App\Core\Models\Review;
use Config;

class SendReviewRequestCommand extends ScheduledCommand {

	 /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'varaa:send-review-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send review request to consumers';

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
        // Set `send_review_request` === false in config/app.php to stop
        // littering laravel.log
        if (Config::get('app.send_review_request', true)) {
           Review::sendReviewRequest();
        }
    }

}
