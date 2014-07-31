<?php namespace Cmd\Migrate;

class TimeSlot extends Base {

    protected $autoIncrementFields = ['id'];
    protected $tablePrefix = 'ts_';
    protected $tablePattern = '_ts_booking_bookings';

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
                'booking_roles',
                'booking_countries',
            ];

            foreach ($tables as $table) {
                $this->migrateTable($table);
            }

            $this->migrateTable('booking_users', [
                'role_id' => 'booking_roles',
            ]);

            $this->migrateTable('booking_calendars', [
                'user_id' => 'booking_users',
            ]);

            $this->migrateTable('booking_bookings', [
                'calendar_id' => 'booking_calendars',
                'customer_country' => 'booking_countries',
            ]);

            $this->migrateTable('booking_bookings_slots', [
                'booking_id' => 'booking_bookings',
            ]);

            $this->migrateTable('booking_dates', [
                'calendar_id' => 'booking_calendars',
            ]);

            $this->migrateTable('booking_options', [
                'calendar_id' => 'booking_calendars',
            ]);

            $this->migrateTable('booking_prices', [
                'calendar_id' => 'booking_calendars',
            ]);

            $this->migrateTable('booking_working_times', [
                'calendar_id' => 'booking_calendars',
            ]);
        }
    }
}
