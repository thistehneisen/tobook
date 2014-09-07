<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFdFlashSolds extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('fd_flash_solds', function ($t) {
		    $t->engine ='InnoDB';
		    $t->increments('id')->unsigned();
		    $t->integer('flash_id')->unsigned();
		    $t->string('coupon_code', 16);
		    $t->boolean('is_used');
		    $t->boolean('is_paid');
		    $t->timestamps();
		    $t->integer('consumer_id')->unsigned();
		    $t->foreign('consumer_id')->references('id')->on('consumers');
		    $t->foreign('flash_id')->references('id')->on('fd_flashs');
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
	    Schema::table('fd_flash_solds', function ($t) {
	        $t->dropForeign('fd_flash_solds_consumer_id_foreign');
	        $t->dropForeign('fd_flash_solds_flash_id_foreign');
	    });
	    Schema::drop('fd_flash_solds');	
	}

}
