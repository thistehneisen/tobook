<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Appointment\Models\Option;
use App, Config, Settings;
use DB;

class UpdateMinDistanceHourUnit extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'varaa:update-min-distance-unit';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update min distance days -> hours unit.';

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
        $options = Option::where('key', '=', 'min_distance')->get();
        foreach ($options as $item) {
            $item->value = intval($item->value) * 24;
            $item->save();
            print('.');
        }
	}

}
