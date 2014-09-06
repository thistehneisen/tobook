<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFdFlashs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('fd_flashs', function ($t) {
		    $t->engine ='InnoDB';
		    $t->increments('id')->unsigned();
		    $t->integer('service_id')->unsigned();
		    $t->decimal('discounted_price', 6, 2);
		    $t->integer('count');
		    $t->date('flash_date');
		    $t->time('start_time');
		    $t->time('end_time');
		    $t->timestamps();
		    $t->integer('user_id')->unsigned();
		    $t->foreign('user_id')->references('id')->on('users')
		        ->onUpdate('cascade')->onDelete('cascade');
		    $t->foreign('service_id')->references('id')->on('fd_services');
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
	    Schema::table('fd_flashs', function ($t) {
	        $t->dropForeign('fd_flashs_user_id_foreign');
	        $t->dropForeign('fd_flashs_service_id_foreign');
	    });
	    Schema::drop('fd_flashs');		
	}
	
}
