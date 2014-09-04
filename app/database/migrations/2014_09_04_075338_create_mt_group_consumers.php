<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMtGroupConsumers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('mt_group_consumers', function ($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->integer('group_id')->unsigned()->nullable();
	        $t->integer('consumer_id')->unsigned()->nullable();
	        $t->timestamps();
	        $t->integer('user_id')->unsigned()->nullable();
	        $t->foreign('user_id')->references('id')->on('users')
	            ->onUpdate('cascade')->onDelete('cascade');
	        $t->foreign('group_id')->references('id')->on('mt_groups');
	        $t->foreign('consumer_id')->references('id')->on('consumers');
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
	    Schema::table('mt_group_consumers', function ($t) {
	        $t->dropForeign('mt_group_consumers_user_id_foreign');
	        $t->dropForeign('mt_group_consumers_group_id_foreign');
	        $t->dropForeign('mt_group_consumers_consumer_id_foreign');
	    });
	    Schema::drop('mt_group_consumers');
	}
}
