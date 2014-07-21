<?php namespace Cmd;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends Command {

	protected function configure() {
		$this->setName('migrate')
		->setDescription('Migrate old data to new database schema')
		->addArgument('module', InputArgument::OPTIONAL);
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$output->writeln($this->green('Hello World'));
	}

	protected function green($text)
	{
		return '<fg=green;options=bold>'.$text.'</fg=green;options=bold>';
	}
}
