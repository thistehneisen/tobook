<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllowMoreNullInAsBookings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("ALTER TABLE `varaa_as_bookings` CHANGE `date` `date` date NULL AFTER `employee_id`;");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("ALTER TABLE `varaa_as_bookings` CHANGE `date` `date` date NOT NULL AFTER `employee_id`;");
	}

}
