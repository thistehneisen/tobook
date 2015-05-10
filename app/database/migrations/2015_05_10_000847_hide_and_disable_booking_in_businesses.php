<?php

use Illuminate\Database\Migrations\Migration;

class HideAndDisableBookingInBusinesses extends Migration
{
    /**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        \DB::statement('ALTER TABLE `' . \DB::getTablePrefix() . 'businesses` CHANGE `is_hidden` `is_hidden` tinyint(1) NOT NULL DEFAULT 1 AFTER `meta_keywords`, CHANGE `is_booking_disabled` `is_booking_disabled` tinyint(1) NOT NULL DEFAULT 1 AFTER `working_hours`');
    }

    /**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
    public function down()
    {
    }

}
