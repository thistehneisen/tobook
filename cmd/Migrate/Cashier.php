<?php namespace Cmd\Migrate;


class Cashier extends Base {

	protected $autoIncrementFields = ['pos_id', 'setting_id', 'id'];
	protected $tablePrefix = 'sma_';
	protected $tablePattern = '_sma_billers';

	public function run()
	{
		$usernames = $this->getUsernames();
		foreach ($usernames as $username) {
			$this->username = $username;

			$this->info('--------------------------------------------------');
			$this->info("Proccessing data of <fg=green;options=bold>{$username}</fg=green;options=bold>", true);
			$this->info('--------------------------------------------------');
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
}
