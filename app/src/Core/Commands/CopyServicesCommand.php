<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Core\Models\User;
use App\Core\Models\Business;
use App\Core\Models\Multilanguage;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Core\Models\Multilanguage;

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
		$source = $this->argument('source');
		$target = $this->argument('target');

		$userSrc = User::find($source);
		$userTgt = User::find($target);
		//Copy categories
		foreach ($userSrc->categories as $category) {
			print('(');
			$cat = $category->replicate();

			$cat->user()->associate($userTgt)->save();

			// Save the translation
			$context = ServiceCategory::getContext();

			$this->copyTranslation($context, $category->id, $cat->id);

			//Copy services
			foreach ($category->services as $service) {
				print('.');
				$srv = $service->replicate();
				$srv->category()->associate($cat)->save();
				$context = Service::getContext();
				$this->copyTranslation($context, $service->id, $srv->id);
			}
			print(')');
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
