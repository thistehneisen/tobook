<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldTotalPriceToAsBookingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_bookings', function(Blueprint $table)
		{
			$table->double('total_price');
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
			$table->dropColumn('total_price');
		});
	}

}
