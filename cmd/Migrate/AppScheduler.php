<?php namespace Cmd\Migrate;
use Doctrine\Common\Inflector\Inflector;

class AppScheduler extends Base {

	protected $autoIncrementFields = ['id'];
	protected $tablePrefix = 'as_';
	protected $tablePattern = '_hey_appscheduler_bookings';

	public function run()
	{
		$usernames = $this->getUsernames();
		foreach ($usernames as $username) {
			$this->map = [];
			
			$this->username = $username;
			$this->info('----------------------------------------------------');
			$this->info("Proccessing data of <fg=green;options=bold>{$username}</fg=green;options=bold>", true);
			$this->info('----------------------------------------------------');
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
				'options',
				'plugin_invoice',
				'services_time'
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

			$this->migrateTable('resources_services', [
				'resources_id' => 'resources',
				'service_id' => 'services',
			]);

			$this->migrateTable('services_extra_service', [
				'extra_id' => 'extra_service',
				'service_id' => 'services',
			]);

			$this->migrateTable('working_times', [
				'foreign_id' => ['type'],
			]);

			$this->migrateTable('plugin_invoice_items', [
				'invoice_id' => 'plugin_invoice',
			]);

            $this->migrateMultiLang();
		}
	}

	public function migrateTable($table, $relationships = [])
	{
		$query = $this->queryBuilder()
			->select('*')
			->from($this->username.'_hey_appscheduler_'.$table, 't');

		return $this->migrate($table, $query, $relationships);
	}


    public function migrateMultiLang()
    {
        $user = $this->getCurrentUser();

        $this->text("Migrating `multi_lang`...", false);

        $stm = $this->queryBuilder()
            ->select('*')
            ->from($this->username.'_hey_appscheduler_multi_lang', 't')
            ->execute();

        while ($row = $stm->fetch()) {
            if ($row['model'] === 'pjCountry') {
                $target = 'plugin_country';
            } else {
                $target = Inflector::pluralize(
                    strtolower(
                        str_replace('pj', '', $row['model'])
                    )
                );
            }

            // Unset ID
            unset($row['id']);
            $oldForeignId = $row['foreign_id'];
            
            if (!isset($this->map[$target][$oldForeignId])) {
                // No need to migrate this obsolete row
                continue;
            }

            $row['foreign_id'] = $this->map[$target][$oldForeignId];
            $row['owner_id']   = $user['nuser_id'];

            $this->db->insert($this->tablePrefix.'multi_lang', $row);
        }

        $this->output->writeln('<fg=green;option=bold>SUCCEEDED</fg=green;option=bold>');
    }
}
