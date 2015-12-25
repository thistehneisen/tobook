<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\DateTime\Scheduler;
use App\Core\Models\Users;
use App\Core\Models\Business;
use App\Appointment\Models\Service;
use DB;
use Redis;

class PopularServicesForSearch extends ScheduledCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'varaa:build-popular-services';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Build popular services for new business listing page.';

	/**
     * When a command should run
     *
     * @param Scheduler $scheduler
     *
     * @return Scheduler
     */
    public function schedule(Schedulable $scheduler)
    {
        return $scheduler->everyMinutes(30);
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$businesses = Business::all();
		$redis = Redis::connection();

		printf('Business count: %d', $businesses->count());

		foreach ($businesses as $business) {
			// There are some deleted users
			if (empty($business->user->id)) 
				continue;
			
			$items = DB::table('as_booking_services')
				->select(['service_id', DB::raw('count(*) as total')])
				->where('user_id', '=', $business->user->id)
				->groupBy('service_id')
				->orderBy('total')
				->limit(2)
				->get();
			$bucket = [];

			$key = sprintf('popular_services_%s', $business->user->id);
        	$redis->del($key);

			foreach ($items as $item) {
				if (!empty($item)) {
					$bucket[] = $item->service_id;
				}
			}

			$redis->set($key, json_encode($bucket));

			print('.');
		}
	}
}
