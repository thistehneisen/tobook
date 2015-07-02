<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Appointment\Models\Service;
use App\Core\Models\Multilanguage;
use DB;

class RecuseOldDataFromBackupImporter extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'varaa:recuse-data-from-backup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update empty or zero value from old backup to current db';

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
        //current data
        $services = Service::where('during', '=', 0)->whereNull('deleted_at')->get();

        $serviceCount = $languageCount = 0;

        print("Update service times \n");
        foreach ($services as $service) {
            $backupService = DB::connection('mysql_backup')->table('as_services')->where('id', '=', $service->id)->first();

            if (!empty($backupService->id) && (int)$backupService->during !== 0) {
                printf("Service Id : %s - Service name : %s \n", $backupService->id, $backupService->name);
                printf("During : %s - After : %s - Before : %s \n", $backupService->during, $backupService->before, $backupService->after);
                print("-----\n");
                $service->during = $backupService->during;
                $service->after  = $backupService->after;
                $service->before = $backupService->before;
                $service->save();
                $serviceCount++;
            }

        }

        print("Update multi languages \n");
        //current multi language
        $mulilangs = Multilanguage::where('value', '=', '')->get();

        foreach ($mulilangs as $mulilang) {
            $backupMultilang = DB::connection('mysql_backup')->table('multilanguage')->where('id', '=', $mulilang->id)->first();

            if (empty($mulilang->value) && !empty($backupMultilang->value)) {
                printf("Multilanguage Id : %s - Value : %s \n", $backupMultilang->id, $backupMultilang->value);
                $mulilang->value = $backupMultilang->value;
                $mulilang->save();
                $languageCount++;
            }
        }
        print("--------------------------------------------------\n");
        printf("Number of services are update: %s \n", $serviceCount);
        printf("Number of multi-language entries are update: %s\n", $languageCount);
	}

}
