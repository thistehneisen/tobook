<?php namespace Cmd\Migrate;

class Restaurant extends Base {

    protected $autoIncrementFields = ['id'];
    protected $tablePrefix = 'rb_';
    protected $tablePattern = '_restaurant_booking_bookings';

    public function run()
    {
        $usernames = $this->getUsernames();
        // $usernames = ['Bcare'];
        foreach ($usernames as $username)
        {
            $this->map = [];
            
            $this->username = $username;
            $this->info('----------------------------------------------------');
            $this->info("Proccessing data of <fg=green;options=bold>{$username}</fg=green;options=bold>", true);
            $this->info('----------------------------------------------------');
            $tables = [
                'restaurant_booking_bookings',
                'restaurant_booking_menu',
                'restaurant_booking_tables',
                'restaurant_booking_countries',
                'restaurant_booking_dates',
                'restaurant_booking_options',
                'restaurant_booking_roles',
                'restaurant_booking_service',
                'restaurant_booking_template',
                'restaurant_booking_vouchers',
                'restaurant_booking_working_times',
            ];
            foreach ($tables as $table) {
                $this->migrateTable($table);
            }

            $this->migrateTable('restaurant_booking_bookings_menu', [
                'booking_id' => 'restaurant_booking_bookings',
                'menu_id'    => 'restaurant_booking_menu'
            ]);

            $this->migrateTable('restaurant_booking_bookings_tables', [
                'booking_id' => 'restaurant_booking_bookings',
                'table_id'    => 'restaurant_booking_menu'
            ]);
            
            $this->migrateTablesGroup();

            $this->migrateTable('restaurant_booking_bookings_tables_group', [
                'booking_id'      => 'restaurant_booking_bookings',
                'tables_group_id' => 'restaurant_booking_tables_group'
            ]);

            $this->migrateTable('restaurant_booking_users', [
                'role_id' => 'restaurant_booking_roles',
            ]);


        }
    }

    public function migrateTable($table, $relationships = [])
    {
        $query = $this->queryBuilder()
            ->select('*')
            ->from($this->username.'_'.$table, 't');

        return $this->migrate($table, $query, $relationships);
    }

    public function migrateTablesGroup()
    {
        $user = $this->getCurrentUser();

        $this->text("Migrating `restaurant_booking_tables_group`...", false);

        $stm = $this->queryBuilder()
            ->select('*')
            ->from($this->username.'_restaurant_booking_tables_group', 't')
            ->execute();

        while ($row = $stm->fetch()) {
            unset($row['id']);

            $tableIds = [];
            foreach (explode(',', $row['tables_id']) as $tableId) {
                $tableId = (int) $tableId;
                if (isset($this->map['restaurant_booking_tables'][$tableId])) {
                    $tableIds[] = $this->map['restaurant_booking_tables'][$tableId];
                }
            }

            $row['tables_id'] = implode(', ', $tableIds);
            $row['owner_id']  = $user['nuser_id'];

            $this->db->insert($this->tablePrefix.'restaurant_booking_tables_group', $row);
        }

        $this->output->writeln('<fg=green;option=bold>SUCCEEDED</fg=green;option=bold>');
    }

}
