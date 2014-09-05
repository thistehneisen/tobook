<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMtHistorys extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('mt_historys', function ($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->string('type', 8);
	        $t->integer('campaign_id')->unsigned()->nullable();
	        $t->integer('sms_id')->unsigned()->nullable();
	        $t->integer('group_id')->unsigned();
	        $t->timestamps();
	        $t->integer('user_id')->unsigned();
	        $t->foreign('user_id')->references('id')->on('users')
	            ->onUpdate('cascade')->onDelete('cascade');
	        $t->foreign('campaign_id')->references('id')->on('mt_campaign');
	        $t->foreign('sms_id')->references('id')->on('mt_sms');
	        $t->foreign('group_id')->references('id')->on('mt_groups');
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
	    Schema::table('mt_historys', function ($t) {
	        $t->dropForeign('mt_historys_user_id_foreign');
	        $t->dropForeign('mt_historys_campaign_id_foreign');
	        $t->dropForeign('mt_historys_sms_id_foreign');
	        $t->dropForeign('mt_historys_group_id_foreign');
	    });
	    Schema::drop('mt_historys');
	}

}
