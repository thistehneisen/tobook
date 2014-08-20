<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsBookingPaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_booking_payments', function(Blueprint $table)
		{
			$table->increments('id');
            $table->unsignedInteger('booking_id');
            $table->string('cc_type');
            $table->string('cc_number');
            $table->string('cc_expired_month');
            $table->string('cc_expired_year');
            $table->string('cc_expired_code');
            $table->string('status');
            $table->foreign('booking_id')->references('id')->on('as_bookings')->onDelete('cascade');
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
		Schema::drop('as_booking_payments');
	}

}
