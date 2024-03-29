<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotesToAsBookings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('as_bookings', function(Blueprint $table)
        {
            $table->text('notes')->after('ip')->nullable();
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
            $table->dropColumn('notes');
        });
	}

}
