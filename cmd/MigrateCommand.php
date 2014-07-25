<?php namespace Cmd;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class MigrateCommand extends Command {

	protected function configure() {
		$this->setName('migrate')
		->setDescription('Migrate old data to new database schema')
		->addArgument('module', InputArgument::REQUIRED, 'Module to be imported');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$config = require realpath(__DIR__.'/../config.php');
		$db = DriverManager::getConnection([
			'dbname'   => $config['db']['name'],
			'user'     => $config['db']['user'],
			'password' => $config['db']['password'],
			'host'     => $config['db']['host'],
			'driver'   => 'pdo_mysql',
		], new Configuration);

		// Fix unknow `enum` type
		// http://wildlyinaccurate.com/doctrine-2-resolving-unknown-database-type-enum-requested
		$platform = $db->getDatabasePlatform();
		$platform->registerDoctrineTypeMapping('enum', 'string');

		// @todo: Based on passed `module`, create corresponding class
		$module = $input->getArgument('module');
		$className = '\Cmd\Migrate\\'.ucfirst($module);

		(new $className($output, $db))->run();
	}
}
