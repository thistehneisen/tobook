<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFdServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('fd_services', function ($t) {
		    $t->engine ='InnoDB';
		    $t->increments('id')->unsigned();
		    $t->string('name', 64);
		    $t->integer('length');
		    $t->decimal('price', 6, 2);
		    $t->integer('category_id')->unsigned();
		    $t->string('sms_confirmation', 32);
		    $t->string('account_owner', 64);
		    $t->string('bank_account_number', 64);
		    $t->timestamps();
		    $t->integer('user_id')->unsigned();
		    $t->foreign('user_id')->references('id')->on('users')
		        ->onUpdate('cascade')->onDelete('cascade');
		    $t->foreign('category_id')->references('id')->on('fd_service_category');
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
	    Schema::table('fd_services', function ($t) {
	        $t->dropForeign('fd_services_user_id_foreign');
	        $t->dropForeign('fd_services_category_id_foreign');
	    });
	    Schema::drop('fd_services');		
	}

}
