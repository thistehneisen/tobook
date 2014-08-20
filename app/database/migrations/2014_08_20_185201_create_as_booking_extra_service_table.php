<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsBookingExtraServiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_booking_extra_service', function(Blueprint $table)
		{
			$table->increments('id');
            $table->unsignedInteger('booking_id');
            $table->unsignedInteger('extra_service_id');
            $table->data('date');
            $table->foreign('booking_id')->references('id')->on('as_bookings')->onDelete('cascade');
            $table->foreign('extra_service_id')->references('id')->on('as_extra_services')->onDelete('cascade');
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
		Schema::drop('as_booking_extra_service');
	}

}
