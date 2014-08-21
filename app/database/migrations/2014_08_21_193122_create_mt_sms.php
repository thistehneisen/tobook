<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMtSms extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('mt_sms', function ($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->string('title', 64);
	        $t->string('content', 160);
	        $t->timestamps();
	        $t->integer('user_id')->unsigned();
	        $t->foreign('user_id')->references('id')->on('users')
	            ->onUpdate('cascade')->onDelete('cascade');
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
	    Schema::table('mt_sms', function ($t) {
	        $t->dropForeign('mt_sms_user_id_foreign');
	    });
	    Schema::drop('mt_sms');
	}

}
