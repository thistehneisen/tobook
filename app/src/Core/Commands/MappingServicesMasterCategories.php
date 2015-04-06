<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Appointment\Models\Service;
use App\Appointment\Models\MasterCategory;
use App\Appointment\Models\TreatmentType;
use Config;

class MappingServicesMasterCategories extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:mapping-services-master-categories-treatments';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Mapping services to selected master category and treatment type';

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
		$path = $this->argument('path');

        $masterCategories = MasterCategory::get()->lists('id', 'name');
        $treatmentTypes   = TreatmentType::get()->lists('id', 'name');

        $file = fopen(realpath($path),"r");
        while (!feof($file)) {
            $data = fgetcsv($file);
            if(!empty($masterCategories[$data[1]]) && $treatmentTypes[$data[2]]){
                $serviceId = (int) $data[0];
                $masterCategoryId = (int) $masterCategories[trim($data[1])];
                $treatmentTypeId = (int) $treatmentTypes[trim($data[2])];
                $this->updateService($serviceId, $masterCategoryId, $treatmentTypeId);
            }
        }
        fclose($file);
	}

    private function updateService($serviceId, $masterCategoryId, $treatmentTypeId)
    {
        $service        = Service::find($serviceId);
        $masterCategory = MasterCategory::find($masterCategoryId);
        $treatmentType  = TreatmentType::find($treatmentTypeId);

        $service->masterCategory()->associate($masterCategory);
        $service->treatmentType()->associate($treatmentType);
        $service->save();
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('path', InputArgument::REQUIRED, 'File path to mapping csv file.'),
		);
	}

}
