<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVaraaAsBookingServiceRoomsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_booking_service_rooms', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('booking_service_id')->nullable();
            $table->unsignedInteger('room_id');
            $table->foreign('room_id')
                ->references('id')
                ->on('as_rooms')
                ->onDelete('cascade');
            $table->foreign('booking_service_id')
                ->references('id')
                ->on('as_booking_services')
                ->onDelete('cascade');
            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('as_booking_service_rooms');
	}

}
