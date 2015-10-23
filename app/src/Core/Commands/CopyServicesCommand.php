<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Core\Models\User;
use App\Core\Models\Business;
use App\Core\Models\Multilanguage;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\ServiceExtraService;

class CopyServicesCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'varaa:copy-services';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Copy services from user to user';

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
		$original = $this->argument('original');
		$target   = $this->argument('target');

		$userSource = User::find($original);
		$userTarget = User::find($target);
		//Copy categories
		foreach ($userSource->asServiceCategories as $category) {
			print('(');
			$_category = $category->replicate();
			$_category->user()->associate($userTarget)->save();
			// Replicate the translation
			$context = ServiceCategory::getContext();
			$this->copyTranslation($context, $category->id, $_category->id);
			//Copy services
			$this->copyServices($category, $_category, $userTarget);
			print(')');
		}
	}

	public function copyServices($source, $target, $userTarget)
	{
		foreach ($source->services as $service) {
			print('.');
			$srv = $service->replicate();
			$srv->category()->associate($target);
			$srv->user()->associate($userTarget)->save();
			// Replicate the translation
			$context = Service::getContext();
			$this->copyTranslation($context, $service->id, $srv->id);

			$this->copyServiceTimes($service, $target);
			$this->copyExtraService($service, $target, $userTarget);
		}
	}

	public function copyServiceTimes($source, $target)
	{
		foreach ($source->serviceTimes as $serviceTime) {
			print('@');
			$_serviceTime = $serviceTime->replicate();
			$_serviceTime->service()->associate($target)->save();
			$context = ServiceTime::getContext();
			$this->copyTranslation($context, $serviceTime->id, $_serviceTime->id);
		}
	}

	public function copyExtraService($source, $target, $userTarget)
	{
		foreach ($source->extraServices as $extraService) {
			print('x');
			$_extraService = $extraService->replicate();
			$_extraService->user()->associate($userTarget)->save();
			// Link extra service and service
			$serviceExtraService = new ServiceExtraService();
            $serviceExtraService->service()->associate($target);
            $serviceExtraService->extraService()->associate($_extraService);
            $serviceExtraService->save();
		}
	}

	public function copyTranslation($context, $object_id, $new_id)
	{
		$keys = ['name', 'description'];
		foreach ($keys as $key) {
			$multilangs = Multilanguage::where('context', '=', $context . $object_id)
				->where('key', '=' , $key)->get();

			foreach ($multilangs as $multilang) {
				$lang = $multilang->replicate();
				$lang->context = $context . $new_id;
				$lang->save();
			}
        }

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('original', InputArgument::REQUIRED, 'Original user'),
			array('target', InputArgument::REQUIRED, 'Target user'),
		);
	}
}
