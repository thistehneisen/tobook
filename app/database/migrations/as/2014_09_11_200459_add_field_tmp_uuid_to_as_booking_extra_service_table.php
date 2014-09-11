<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldTmpUuidToAsBookingExtraServiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_booking_extra_services', function(Blueprint $table)
		{
			$table->string('tmp_uuid');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('as_booking_extra_services', function(Blueprint $table)
		{
			$table->dropColumn('tmp_uuid');
		});
	}

}
