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
                'bookings',
                'menu',
                'tables',
                'countries',
                'dates',
                'options',
                'roles',
                'service',
                'template',
                'vouchers',
                'working_times',
                'users'
            ];
            foreach ($tables as $table) {
                $this->migrateTable($table);
            }

            $this->migrateTable('bookings_menu', [
                'booking_id' => 'bookings',
                'menu_id'    => 'menu'
            ]);

            $this->migrateTable('bookings_tables', [
                'booking_id' => 'bookings',
                'table_id'    => 'menu'
            ]);
            
            $this->migrateTablesGroup();

            $this->migrateTable('bookings_tables_group', [
                'booking_id'      => 'bookings',
                'tables_group_id' => 'tables_group'
            ]);
        }
    }

    public function migrateTable($table, $relationships = [])
    {
        $query = $this->queryBuilder()
            ->select('*')
            ->from($this->username.'_restaurant_booking_'.$table, 't');

        return $this->migrate($table, $query, $relationships);
    }

    public function migrateTablesGroup()
    {
        $user = $this->getCurrentUser();

        $this->text("Migrating `tables_group`...", false);

        $stm = $this->queryBuilder()
            ->select('*')
            ->from($this->username.'_restaurant_booking_tables_group', 't')
            ->execute();

        while ($row = $stm->fetch()) {
            unset($row['id']);

            $tableIds = [];
            foreach (explode(',', $row['tables_id']) as $tableId) {
                $tableId = (int) $tableId;
                if (isset($this->map['tables'][$tableId])) {
                    $tableIds[] = $this->map['tables'][$tableId];
                }
            }

            $row['tables_id'] = implode(', ', $tableIds);
            $row['owner_id']  = $user['nuser_id'];

            $this->db->insert($this->tablePrefix.'tables_group', $row);
        }

        $this->output->writeln('<fg=green;option=bold>SUCCEEDED</fg=green;option=bold>');
    }

}
