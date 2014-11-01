<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldModifyTimeToSignedIntInAsBookingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$queries = [
            "ALTER TABLE `varaa_as_bookings` CHANGE `modify_time` `modify_time` int(10) NULL",
            "ALTER TABLE `varaa_as_booking_services` CHANGE `modify_time` `modify_time` int(10) NULL"
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
		//
	}

}
