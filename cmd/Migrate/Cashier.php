<?php namespace Cmd\Migrate;


class Cashier extends Base {

	protected $schemaManager;

	public function __construct($output, $db)
	{
		parent::__construct($output, $db);
		$this->schemaManager = $this->db->getSchemaManager();
	}

	public function run()
	{
		$this->listTables();
	}

	protected function listTables()
	{
		$this->info('Getting a list of username to be proceeded...');
		$usernames = [];

		// Get the list of tables
		$tables = $this->schemaManager->listTables();
		foreach ($tables as $table)
		{
			$name = $table->getName();
			if (strpos($name, '_sma_billers') !== false) {
				$usernames[] = substr($name, 0, strpos($name, '_'));
			}
		}
		$this->info('Found '.count($usernames).' users');
	}

}
