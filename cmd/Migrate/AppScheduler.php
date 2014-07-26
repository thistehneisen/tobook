<?php namespace Cmd\Migrate;

class AppScheduler extends Base {

	protected $autoIncrementFields = ['id'];
	protected $tablePrefix = 'as_';
	protected $tablePattern = '/([a-z0-9]+)_hey_appscheduler_(.*)/i';

	public function run()
	{
		$this->username = 'kcjpop';

		$tables = [
			'roles',
			'plugin_country',
			'services_category',
			'resources',
			'custom_times',
			'extra_service',
			'fields',
			'formstyle',
			'plugin_invoice_config',
			'plugin_locale',
			'plugin_locale_languages',
			'plugin_log',
			'plugin_log_config',
			'plugin_one_admin',
			'plugin_sms',
		];
		foreach ($tables as $table) {
			$this->migrateTable($table);
		}

		$this->migrateTable('users', [
			'role_id' => 'roles'
		]);

		$this->migrateTable('calendars', [
			'user_id' => 'users'
		]);

		$this->migrateTable('bookings', [
			'calendar_id' => 'calendars',
			'c_country_id' => 'plugin_country'
		]);

		$this->migrateTable('services', [
			'calendar_id' => 'calendars',
			'category_id' => 'services_category'
		]);

		$this->migrateTable('employees', [
			'calendar_id' => 'calendars',
		]);

		$this->migrateTable('bookings_services', [
			'booking_id' => 'bookings',
			'service_id' => 'services',
			'employee_id' => 'employees',
			'resources_id' => 'resources',

		]);

		$this->migrateTable('bookings_extra_service', [
			'booking_id' => 'bookings',
			'service_id' => 'services',
			'extra_id'   => 'extra_service',
		]);

		$this->migrateTable('bookings_status', [
			'booking_id' => 'bookings',
		]);

		$this->migrateTable('dates', [
			'foreign_id' => ['type'],
		]);

		// $this->migrateTable('multi_lang', [
		// 	'foreign_id' => '',
		// ]);
		
		// $this->migrateTable('options', [
		// 	'foreign_id' => '',
		// 	'tab_id' => '',
		// ]);

		$this->migrateTable('employees_custom_times', [
			'employee_id' => 'employees',
			'customtime_id' => 'custom_times',
		]);

		$this->migrateTable('employees_freetime', [
			'employee_id' => 'employees',
		]);

		$this->migrateTable('employees_services', [
			'employee_id' => 'employees',
			'service_id' => 'services',
		]);

		// $this->migrateTable('plugin_invoice', [
		// 	'foreign_id' => '',
		// 	'order_id' => '',
		// ]);

		// $this->migrateTable('plugin_invoice_items', [
		// 	'invoice_id' => '',
		// 	'order_id' => '',
		// ]);

		$this->migrateTable('resources_services', [
			'resources_id' => 'resources',
			'service_id' => 'services',
		]);

		$this->migrateTable('services_extra_service', [
			'extra_id' => 'extra_service',
			'service_id' => 'services',
		]);

		// $this->migrateTable('services_time', [
		// 	'foreign_id' => '',
		// ]);

		$this->migrateTable('working_times', [
			'foreign_id' => ['type'],
		]);

	}

	public function migrateTable($table, $relationships = [])
	{
		$query = $this->queryBuilder()
			->select('*')
			->from($this->username.'_hey_appscheduler_'.$table, 't');

		return $this->migrate($table, $query, $relationships);
	}

}
