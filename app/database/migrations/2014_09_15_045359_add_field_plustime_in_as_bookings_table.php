<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldPlustimeInAsBookingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_bookings', function(Blueprint $table)
		{
			$table->integer('plustime')->after('modify_time')->default(0);
		});
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
			$table->dropColumn('plustime');
		});
	}

}
