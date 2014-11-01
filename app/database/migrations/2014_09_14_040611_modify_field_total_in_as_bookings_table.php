<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModifyFieldTotalInAsBookingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("ALTER TABLE `varaa_as_bookings` CHANGE COLUMN `total` `total` int(10) NULL DEFAULT '0';");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('as_bookings', function(Blueprint $table)
		{
			$table->dropColumn('total');
		});
	}

}
