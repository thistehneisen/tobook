<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllowNullInTableAsBookings extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $queries = [
            "ALTER TABLE `varaa_as_bookings` CHANGE `user_id` `user_id` int(10) unsigned NULL AFTER `uuid`, CHANGE `consumer_id` `consumer_id` int(10) unsigned NULL AFTER `user_id`, CHANGE `employee_id` `employee_id` int(10) unsigned NULL AFTER `consumer_id`, COMMENT='';",
            "ALTER TABLE `varaa_as_booking_services` CHANGE `user_id` `user_id` int(10) unsigned NULL AFTER `tmp_uuid`, CHANGE `booking_id` `booking_id` int(10) unsigned NULL AFTER `user_id`, CHANGE `service_id` `service_id` int(10) unsigned NULL AFTER `booking_id`, CHANGE `employee_id` `employee_id` int(10) unsigned NULL AFTER `service_time_id`, COMMENT='';"
        ];
        foreach ($queries as $sql) {
            DB::statement($sql);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $queries = [
            "ALTER TABLE `varaa_as_bookings` CHANGE `user_id` `user_id` int(10) unsigned NOT NULL AFTER `uuid`, CHANGE `consumer_id` `consumer_id` int(10) unsigned NOT NULL AFTER `user_id`, CHANGE `employee_id` `employee_id` int(10) unsigned NOT NULL AFTER `consumer_id`, COMMENT='';",
            "ALTER TABLE `varaa_as_booking_services` CHANGE `user_id` `user_id` int(10) unsigned NOT NULL AFTER `tmp_uuid`, CHANGE `booking_id` `booking_id` int(10) unsigned NOT NULL AFTER `user_id`, CHANGE `service_id` `service_id` int(10) unsigned NOT NULL AFTER `booking_id`, CHANGE `employee_id` `employee_id` int(10) unsigned NOT NULL AFTER `service_time_id`, COMMENT='';"
        ];
        foreach ($queries as $sql) {
            DB::statement($sql);
        }
    }

}
