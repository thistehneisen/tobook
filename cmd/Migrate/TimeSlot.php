<?php namespace Cmd\Migrate;

class TimeSlot extends Base {

    protected $autoIncrementFields = ['id'];
    protected $tablePrefix = 'ts_';
    protected $tablePattern = '_ts_booking_bookings';

    public function migrateTable($table, $relationships = [])
    {
        $query = $this->queryBuilder()
            ->select('*')
            ->from($this->username.'_ts_booking_'.$table, 't');

        return $this->migrate($table, $query, $relationships);
    }

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
                'users',
            ];

            foreach ($tables as $table) {
                $this->migrateTable($table);
            }

            $this->migrateTable('calendars', [
                'user_id' => 'users',
            ]);

            $this->migrateTable('bookings', [
                'calendar_id' => 'calendars',
            ]);

            $this->migrateTable('bookings_slots', [
                'booking_id' => 'bookings',
            ]);

            $this->migrateTable('dates', [
                'calendar_id' => 'calendars',
            ]);

            $this->migrateTable('options', [
                'calendar_id' => 'calendars',
            ]);

            $this->migrateTable('prices', [
                'calendar_id' => 'calendars',
            ]);

            $this->migrateTable('working_times', [
                'calendar_id' => 'calendars',
            ]);
        }
    }
}
