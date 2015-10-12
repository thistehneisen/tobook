<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Appointment\Models\EmployeeCustomTime;
use App\Appointment\Models\Employee;

class FixWorkshiftDuplicates extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'varaa:fix-duplicate-issues';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Fix workshift duplicate issue';

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
		$duplicates = [];
		$customTimes = EmployeeCustomTime::whereNull('deleted_at')
			->orderBy('employee_id')->orderBy('created_at', 'desc')->get();

		foreach ($customTimes as $customTime) {
			$duplicates[$customTime->employee_id][$customTime->date][] =  $customTime;
		}

		foreach ($duplicates as $dupes) {
			foreach ($dupes as $date) {
				if(count($date) < 2) {
					continue;
				}
				//Keep biggest id element
				array_shift($date);
				$first = current($date);
				printf("\nRemove duplicate workshift for employee: %d\n", $first->employee_id);
				foreach ($date as $custom) {
					print(".");
					$custom->delete();
				}			
			}
		}
	}

}
