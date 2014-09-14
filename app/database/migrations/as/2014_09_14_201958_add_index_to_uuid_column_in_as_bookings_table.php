<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToUuidColumnInAsBookingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('as_bookings', function(Blueprint $table)
        {
            $table->unique('uuid');
        });

        Schema::table('as_booking_services', function(Blueprint $table)
        {
            $table->unique('tmp_uuid');
        });
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
