<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Indatus\Dispatcher\Scheduling\ScheduledCommand;
use Indatus\Dispatcher\Scheduling\Schedulable;
use Indatus\Dispatcher\Drivers\DateTime\Scheduler;
use App\Core\Models\Users;
use App\Core\Models\Business;
use App\Appointment\Models\MasterCategory;
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
		
		printf('Business count: %d', $businesses->count());

		$categories = MasterCategory::getAll();

		foreach ($businesses as $business) {
			// There are some deleted users
			if (empty($business->user->id)) 
				continue;
			
			foreach ($categories as $category) {
				$this->saveCategoryPopularServices($category->id, $business->user->id);

				foreach ($category->treatments as $treament) {
					$this->saveTreatmentPopularServices($treament->id, $business->user->id);
				}
			}

			print('.');
		}
	}

	private function saveTreatmentPopularServices($treatment_type_id, $user_id)
	{
		$redis = Redis::connection();

		$items = DB::table('as_booking_services')
					->select(['as_booking_services.service_id', DB::raw('count(*) as total')])
					->join('as_services', 'as_services.id', '=', 'as_booking_services.service_id')
					->where('as_booking_services.user_id', '=', $user_id)
					->where('as_services.treatment_type_id', '=', $treatment_type_id)
					->groupBy('as_booking_services.service_id')
					->orderBy('total')
					->limit(5)
					->get();
		$bucket = [];

		$key = sprintf('ps_%s_tc_%s', $user_id, $treatment_type_id);
    	$redis->del($key);

		foreach ($items as $item) {
			if (!empty($item)) {
				$bucket[] = $item->service_id;
			}
		}

		$redis->set($key, json_encode($bucket));
	}

	private function saveCategoryPopularServices($category_id, $user_id)
	{
		$redis = Redis::connection();

		$items = DB::table('as_booking_services')
					->select(['as_booking_services.service_id', DB::raw('count(*) as total')])
					->join('as_services', 'as_services.id', '=', 'as_booking_services.service_id')
					->where('as_booking_services.user_id', '=', $user_id)
					->where('as_services.master_category_id', '=', $category_id)
					->groupBy('as_booking_services.service_id')
					->orderBy('total')
					->limit(5)
					->get();
		$bucket = [];

		$key = sprintf('ps_%s_mc_%s', $user_id, $category_id);
    	$redis->del($key);

		foreach ($items as $item) {
			if (!empty($item)) {
				$bucket[] = $item->service_id;
			}
		}

		$redis->set($key, json_encode($bucket));
	}
}
