<?php namespace Cmd\Migrate;


class Cashier extends Base {

	protected $schemaManager;
	protected $currentUser;
	protected $username;

	/**
	 * Contain map from old IDs to new IDs
	 *
	 * @var array
	 */
	protected $map = [];

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
			$this->username = $username;

			$this->info("Proccessing data of [{$username}]");
			$this->migrateGroups();
			$this->migrateUsers();
			$this->migrateUserGroup();
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
		 || $this->currentUser['vuser_login'] !== $this->username) {
			// Get user information from tbl_user_mast
			$stm = $this->queryBuilder()
				->select('*')
				->from('tbl_user_mast', 'u')
				->where('u.vuser_login = ?')
				->setParameter(0, $this->username)
				->execute();

			$user = $stm->fetch();
			if ($user === false) {
				$this->error("Cannot find information of $this->username. Retry later!");
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

		$this->text('Migrating groups...');
		// Get all existing groups
		$stm = $this->queryBuilder()
			->select('*')
			->from($this->username.'_sma_groups', 'g')
			->execute();
		
		while($row = $stm->fetch()) {
			$oldId = $row['id'];
			unset($row['id']);
			$row['owner_id'] = $user['nuser_id'];
			$this->db->insert('sma_groups', $row);

			// Map old group ID to newly created ID
			$map[$oldId] = $this->db->lastInsertId();
		}

		$this->map['groups'] = $map;
		return $map;
	}

	public function migrateUsers()
	{
		$user = $this->getCurrentUser();
		if ($user === false) {
			return;
		}

		$this->text("Migrating users...");
		$map = [];

		// Get all sub-users
		$stm = $this->queryBuilder()
			->select('*')
			->from($this->username.'_sma_users', 'u')
			->execute();

		while ($row = $stm->fetch()) {
			$oldId = $row['id'];

			unset($row['id']);
			$row['owner_id'] = $user['nuser_id'];

			// Move from old table to correct one
			$this->db->insert('sma_users', $row);

			$map[$oldId] = $this->db->lastInsertId();
		}

		$this->map['users'] = $map;
		return $map;
	}

	public function migrateUserGroup()
	{
		$this->text('Migrating users and groups relationship...');

		$user = $this->getCurrentUser();
		if ($user === false || 
			empty($this->map['groups']) || 
			empty($this->map['users'])) {
			$this->error('ERROR: Either Group or User map is empty.');
			return;
		}

		$stm = $this->queryBuilder()
			->select('*')
			->from($this->username.'_sma_users_groups', 'ug')
			->execute();

		while ($row = $stm->fetch()) {
			unset ($row['id']);

			$userId  = $row['user_id'];
			$groupId = $row['group_id'];

			$row['user_id']  = $this->map['users'][$userId];
			$row['group_id'] = $this->map['groups'][$groupId];
			$row['owner_id'] = $user['nuser_id'];

			$this->db->insert('sma_users_groups', $row);
		}
	}

}
