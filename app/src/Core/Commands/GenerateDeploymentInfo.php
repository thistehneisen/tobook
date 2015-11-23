<?php namespace App\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Carbon\Carbon;

class GenerateDeploymentInfo extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'varaa:generate-deployment-info';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
		$template = "Date: {date}
Entity: {entity}
Branch/tag: {branch}
Commit: {commit}
Version:{version}
";
		$now    = Carbon::now();
		$date   = $now->format('l jS F Y h:i:s A');
		$branch = $this->exec('git symbolic-ref --short -q HEAD');
		$entity = $this->exec('php artisan env');
		$commit = $this->exec("git log --pretty=format:\'%h\' -n 1");
		$version = $this->exec('git describe');

		$entity = str_replace('Current application environment: ', '', $entity);

		$template = str_replace('{date}', trim($date), $template);
		$template = str_replace('{entity}', trim($entity), $template);
		$template = str_replace('{branch}', trim($branch), $template);
		$template = str_replace('{commit}', trim($commit), $template);
		$template = str_replace('{version}', trim($version), $template);
		//$this->info($template);
		file_put_contents('./public/rev.txt', $template);
	}

	public function exec($command)
	{
		$process = new Process($command);
		$process->run();
		
		// executes after the command finishes
		if (!$process->isSuccessful()) {
		    throw new ProcessFailedException($process);
		}

		return $process->getOutput();
	}

}
