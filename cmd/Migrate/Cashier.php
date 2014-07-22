<?php namespace Cmd\Migrate;


class Cashier extends Base {

	protected $schemaManager;
	protected $currentUser;
	protected $prefix;

	public function __construct($output, $db)
	{
		parent::__construct($output, $db);
		$this->schemaManager = $this->db->getSchemaManager();
	}

	protected function queryBuilder()
	{
		return $this->db->createQueryBuilder();
	}

	public function run()
	{
		// $usernames = $this->getUsernames();
		$usernames = ['jenistar'];
		foreach ($usernames as $username) {
			$this->prefix = $username;
			$this->migrateGroups();
		}
	}

	protected function getUsernames()
	{
		$this->text('Getting a list of users to be proceeded...');
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
		$this->text('Found '.count($usernames).' users');

		return $usernames;
	}

	public function getCurrentUser()
	{
		if ($this->currentUser === false
		 || $this->currentUser['vuser_login'] !== $this->prefix) {
			// Get user information from tbl_user_mast
			$stm = $this->queryBuilder()
				->select('*')
				->from('tbl_user_mast', 'u')
				->where('u.vuser_login = ?')
				->setParameter(0, $this->prefix)
				->execute();

			$user = $stm->fetch();
			if ($user === false) {
				$this->error("Cannot find information of $this->prefix. Retry later!");
			}

			$this->currentUser = $user;
		}

		return $this->currentUser;
	}

	public function migrateGroups()
	{
		$user = $this->getCurrentUser();
		if ($user === false) {
			return;
		}

		// Array to map old group IDs to new group IDs
		$map = [];

		$table = $this->prefix.'_sma_groups';
		// Get all existing groups
		$stm = $this->queryBuilder()
			->select('*')
			->from($table, 'g')
			->execute();
		
		while($row = $stm->fetch()) {
			$oldId = $row['id'];
			unset($row['id']);
			$row['owner_id'] = $user['nuser_id'];
			$this->db->insert('sma_groups', $row);

			// Map old group ID to newly created ID
			$map[$oldId] = $this->db->lastInsertId();
		}

		return $map;
	}

}
