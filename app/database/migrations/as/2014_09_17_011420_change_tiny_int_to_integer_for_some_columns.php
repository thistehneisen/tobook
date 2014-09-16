<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTinyIntToIntegerForSomeColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        //Bookings
        DB::statement("ALTER TABLE `varaa_as_bookings` CHANGE `total` `total` int(10) unsigned NULL default 0");
        DB::statement("ALTER TABLE `varaa_as_bookings` CHANGE `modify_time` `modify_time` int(10) unsigned NULL default 0");

        //Booking services
        DB::statement("ALTER TABLE `varaa_as_booking_services` CHANGE `modify_time` `modify_time` int(10) unsigned NULL default 0");

        //Services
        DB::statement("ALTER TABLE `varaa_as_services` CHANGE `length` `length` int(10) unsigned NULL default 0");
        DB::statement("ALTER TABLE `varaa_as_services` CHANGE `before` `before` int(10) unsigned NULL default 0");
        DB::statement("ALTER TABLE `varaa_as_services` CHANGE `during` `during` int(10) unsigned NULL default 0");
        DB::statement("ALTER TABLE `varaa_as_services` CHANGE `after` `after` int(10) unsigned NULL default 0");

        //Extra services
        DB::statement("ALTER TABLE `varaa_as_extra_services` CHANGE `length` `length` int(10) unsigned NULL default 0");


        //Service times
        DB::statement("ALTER TABLE `varaa_as_service_times` CHANGE `length` `length` int(10) unsigned NULL default 0");
        DB::statement("ALTER TABLE `varaa_as_service_times` CHANGE `before` `before` int(10) unsigned NULL default 0");
        DB::statement("ALTER TABLE `varaa_as_service_times` CHANGE `during` `during` int(10) unsigned NULL default 0");
        DB::statement("ALTER TABLE `varaa_as_service_times` CHANGE `after` `after` int(10) unsigned NULL default 0");

        //Resources
        DB::statement("ALTER TABLE `varaa_as_resources` CHANGE `quantity` `quantity` int(10) unsigned NULL default 0");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
