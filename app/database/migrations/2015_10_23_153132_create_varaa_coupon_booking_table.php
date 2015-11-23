<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVaraaCouponBookingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_coupon_booking', function(Blueprint $table)
        {
            $table->unsignedInteger('booking_id');
            $table->unsignedInteger('coupon_id');
            $table->primary('booking_id');//resuable coupon can be used multiple time
            $table->foreign('coupon_id')
                ->references('id')
                ->on('as_coupons')
                ->onDelete('cascade');
            $table->foreign('booking_id')
                ->references('id')
                ->on('as_bookings')
                ->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('as_coupon_booking');
	}

}
