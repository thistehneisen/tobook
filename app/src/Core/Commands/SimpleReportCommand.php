<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Carbon\Carbon;
use DB, Log;

class SimpleReportCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'varaa:simple-report';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate simple report such as total new bookings, consumers, employees';

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
		$date = $this->argument('date');
        $current = Carbon::now();
        if (!empty($date)) {
            try {
                $current = Carbon::createFromFormat('Y-m-d', $date . '-01');
            } catch (\Exception $ex) {
                $current = Carbon::now();
            }
        }

        $startOfMonth   = $current->startOfMonth()->toDateString();
        $endOfMonth     = $current->endOfMonth()->toDateString();
        $totalEmployees = $this->countEmployees($startOfMonth, $endOfMonth);
        $totalBookings  = $this->countBookings($startOfMonth, $endOfMonth);
        $totalConsumers = $this->countConsumers($startOfMonth, $endOfMonth);

        printf("Total new bookings: %s \n", $totalBookings->total);
        printf("Total new consumers: %s \n", $totalConsumers->total);
        printf("Total new employees: %s \n", $totalEmployees->total);
	}

    public function countEmployees($start, $end)
    {
        $result = DB::table('as_employees')
            ->where('as_employees.created_at', '>=', $start)
            ->where('as_employees.created_at', '<=', $end)
            ->whereNull('as_employees.deleted_at')
            ->select(DB::raw('COUNT(*) as total'))
            ->first();
        return $result;
    }

    public function countBookings($start, $end)
    {
        $result = DB::table('as_bookings')
            ->where('as_bookings.created_at', '>=', $start)
            ->where('as_bookings.created_at', '<=', $end)
            ->whereNull('as_bookings.deleted_at')
            ->select(DB::raw('COUNT(*) as total'))
            ->first();
        return $result;
    }

    public function countConsumers($start, $end)
    {
         $result = DB::table('consumers')
            ->where('consumers.created_at', '>=', $start)
            ->where('consumers.created_at', '<=', $end)
            ->whereNull('consumers.deleted_at')
            ->select(DB::raw('COUNT(*) as total'))
            ->first();
        return $result;
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('date', InputArgument::OPTIONAL, 'Please specify a date (YYYY-MM).'),
		);
	}

}
