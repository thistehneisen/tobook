<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsBookingResourceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_booking_resource', function(Blueprint $table)
        {
            $table->unsignedInteger('booking_id');
            $table->unsignedInteger('resource_id');
            $table->foreign('booking_id')
                ->references('id')
                ->on('as_bookings')
                ->onDelete('cascade');
            $table->foreign('resource_id')
                ->references('id')
                ->on('as_resources')
                ->onDelete('cascade');
            $table->primary(array('booking_id', 'resource_id'));
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('as_booking_resource');
	}

}
