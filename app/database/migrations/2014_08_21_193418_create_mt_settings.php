<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMtSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('mt_settings', function ($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->string('type', 8);
	        $t->integer('campaign_id')->unsigned()->nullable();
	        $t->integer('sms_id')->unsigned()->nullable();
	        $t->string('module_type', 2);
	        $t->integer('counts_prev_booking')->unsigned()->nullable();
	        $t->integer('days_prev_booking')->unsigned()->nullable();
	        $t->timestamps();
	        $t->integer('user_id')->unsigned();
	        $t->foreign('user_id')->references('id')->on('users')
	            ->onUpdate('cascade')->onDelete('cascade');
	        $t->foreign('campaign_id')->references('id')->on('mt_campaign');
	        $t->foreign('sms_id')->references('id')->on('mt_sms');
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
	    Schema::table('mt_settings', function ($t) {
	        $t->dropForeign('mt_settings_user_id_foreign');
	        $t->dropForeign('mt_settings_campaign_id_foreign');
	        $t->dropForeign('mt_settings_sms_id_foreign');
	    });
	    Schema::drop('mt_settings');
	}

}
