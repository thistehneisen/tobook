<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFdCouponSolds extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    //
		Schema::create('fd_coupon_solds', function ($t) {
    	    $t->engine ='InnoDB';
    	    $t->increments('id')->unsigned();
    	    $t->integer('coupon_id')->unsigned();
    	    $t->decimal('coupon_code', 6, 2);
    	    $t->boolean('is_used');
    	    $t->boolean('is_paid');
    	    $t->timestamps();
    	    $t->integer('consumer_id')->unsigned();
    	    $t->foreign('consumer_id')->references('id')->on('consumers');
    	    $t->foreign('coupon_id')->references('id')->on('fd_coupons');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	    Schema::table('fd_coupon_solds', function ($t) {
	        $t->dropForeign('fd_flash_solds_consumer_id_foreign');
	        $t->dropForeign('fd_flash_solds_coupon_id_foreign');
	    });
	    Schema::drop('fd_coupon_solds');
	}
}
