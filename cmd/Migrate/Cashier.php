<?php namespace Cmd\Migrate;


class Cashier extends Base {

	protected $schemaManager;
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
		$usernames = $this->getUsernames();
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

			$this->migrateTable('purchases', [
				'warehouse_id' => 'warehouses',
				'supplier_id' => 'suppliers',
			]);

			$this->migrateTable('purchase_items', [
				'purchase_id'    => 'purchases',
				'product_id'     => 'products',
				'default_biller' => 'billers',
			]);

			$this->migrateTable('quotes', [
				'warehouse_id' => 'warehouses',
				'biller_id'    => 'billers',
				'discount_id'  => 'discounts',
				'customer_id'  => 'customers',
			]);

			$this->migrateTable('quote_items', [
				'quote_id'    => 'quotes',
				'product_id'  => 'products',
				'discount_id' => 'discounts',
			]);

			$this->migrateTable('sales', [
				'warehouse_id' => 'warehouses',
				'biller_id'    => 'billers',
				'customer_id'  => 'customers',
				'invoice_type' => 'invoice_types',
				'tax_rate2_id' => 'tax_rates',
				'discount_id'  => 'discounts',
			]);

			$this->migrateTable('sale_items', [
				'sale_id'     => 'sales',
				'product_id'  => 'products',
				'tax_rate_id' => 'tax_rates',
				'discount_id' => 'discounts',
			]);

			$this->migrateTable('settings', [
				'default_warehouse'    => 'warehouses',
				'default_invoice_type' => 'tax_rates',
				'default_tax_rate'     => 'tax_rates',
				'default_tax_rate2'    => 'tax_rates',
				'default_discount'     => 'discounts',
				'dateformat'           => 'date_format',
			]);

			$this->migrateTable('suspended_bills', [
				'customer_id' => 'customers',
			]);

			$this->migrateTable('suspended_items', [
				'suspend_id'  => 'suspended_bills',
				'product_id'  => 'products',
				'tax_rate_id' => 'tax_rates',
				'discount_id' => 'discounts',
			]);

			$this->migrateTable('transfers', [
				'from_warehouse_id' => 'warehouses',
				'to_warehouse_id'   => 'warehouses',
				'tax_rate_id'       => 'tax_rates',
				'discount_id'       => 'discounts',
			]);

			$this->migrateTable('transfer_items', [
				'transfer_id' => 'transfers',
				'product_id'  => 'products',
				'tax_rate_id' => 'tax_rates',
			]);

			$this->migrateTable('warehouses_products', [
				'warehouse_id' => 'warehouses',
				'product_id'   => 'products',
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
			preg_match('/([a-z0-9]+)_sma_billers/i', $table->getName(), $matches);
			if (isset($matches[1])) {
				$usernames[] = $matches[1];
			}
		}
		$this->text('Found '.count($usernames).' users');

		return $usernames;
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
		$this->text('Migrating `'.$table.'`...');

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
		$stm = $query->execute();

		if ($stm->rowCount() === 0) {
			$this->comment("Table `$table` doesn't have data.");
		}

		while ($row = $stm->fetch()) {
			$mapped = isset($row['id']);
			if ($mapped) {
				$oldId = $row['id'];
			}

			// Some auto increment fields need to be removed
			foreach (['setting_id', 'pos_id', 'id'] as $idField) {
				if (isset($row[$idField])) {
					unset($row[$idField]);
				}
			}

			$row['owner_id'] = $user['nuser_id'];

			// Quick fix for reserved keywords
			if (isset($row['sql'])) {
				$quoted = $this->db->quoteIdentifier('sql');
				$row[$quoted] = $row['sql'];
				unset($row['sql']);
			}

			// Map relationships
			$skip = false;
			foreach ($relationships as $field => $tbl) {
				$id = $row[$field];

				// Cannot find ID in the map, maybe target relationship was
				// deleted. So no need to keep this record.
				if (!isset($this->map[$tbl][$id])) {
					$skip = true;
					break;
				}
				$row[$field] = $this->map[$tbl][$id];
			}

			if ($skip === true) {
				continue;
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
