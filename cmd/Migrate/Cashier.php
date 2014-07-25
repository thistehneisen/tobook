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

			$this->info("Proccessing data of <fg=green;options=bold>{$username}</fg=green;options=bold>");
			$tables = [
				'billers',
				'groups',
				'users',
				'warehouses',
				'categories',
				'customers',
				'suppliers',
				'tax_rates',
				'discounts',
				'date_format',
				'comment',
				'deliveries',
				'invoice_types',
			];
			foreach ($tables as $table) {
				$this->migrateTable($table);
			}
			
			$this->migrateTable('users_groups', [
				'user_id'  => 'users',
				'group_id' => 'groups'
			]);

			$this->migrateTable('subcategories', [
				'category_id' => 'categories'
			]);

			$this->migrateTable('calendar', [
				'user_id' => 'users'
			]);

			$this->migrateTable('products', [
				'category_id'    => 'categories',
				'subcategory_id' => 'subcategories',
				'tax_rate'       => 'tax_rates',
			]);

			$this->migrateTable('damage_products', [
				'product_id'   => 'products',
				'warehouse_id' => 'warehouses',
			]);

			$this->migrateTable('pos_settings', [
				'default_category' => 'categories',
				'default_customer' => 'customers',
				'default_biller' => 'billers',
			]);
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

	/**
	 * Shorter version of `migrate()`, in case you don't need to specify query
	 *
	 * @param  string $table         
	 * @param  array $relationships 
	 *
	 * @return array
	 */
	protected function migrateTable($table, $relationships = [])
	{
		return $this->migrate($table, $this->queryBuilder()
			->select('*')
			->from($this->username.'_sma_'.$table, 't'), $relationships);
	}

	/**
	 * Migrate data of a table, store a map of old IDs to new IDs
	 *
	 * @param  string  $table         Table name, should be in plural without
	 *                                `sma` prefix
	 * @param  Stament $query         Statement to get data from that table
	 * @param  array   $relationships Specify the relationships to other tables.
	 * Example:
	 * <code>
	 * ['user_id'  => 'users',
	 * 	'group_id' => 'groups']
	 * </code>
	 *
	 * @return array The map of old IDs to new IDs
	 */
	protected function migrate($table, $query, $relationships = [])
	{
		$user = $this->getCurrentUser();
		if ($user === false) {
			$this->error('ERROR: Cannot get data of current user');
			return;
		}

		foreach ($relationships as $tbl) {
			if (empty($this->map[$tbl])) {
				$this->error("ERROR: Empty map of `$tbl`.");
				return;
			}
		}

		$map = [];
		$this->text('Migrating `'.$table.'`...');
		$stm = $query->execute();

		while ($row = $stm->fetch()) {
			$mapped = isset($row['id']);
			if ($mapped) {
				$oldId = $row['id'];
				unset($row['id']);
			}

			$row['owner_id'] = $user['nuser_id'];

			// Quick fix for reserved keywords
			if (isset($row['sql'])) {
				$quoted = $this->db->quoteIdentifier('sql');
				$row[$quoted] = $row['sql'];
				unset($row['sql']);
			}

			// Map relationships
			foreach ($relationships as $field => $tbl) {
				$id = $row[$field];
				$row[$field] = $this->map[$tbl][$id];
			}

			$this->db->insert('sma_'.$table, $row);

			if ($mapped) {
				$map[$oldId] = $this->db->lastInsertId();
			}
		}

		$this->map[$table] = $map;
		return $map;
	}

}
