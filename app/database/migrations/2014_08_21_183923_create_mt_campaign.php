<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMtCampaign extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('mt_campaign', function ($t) {
		    $t->engine ='InnoDB';
		    $t->increments('id')->unsigned();
		    $t->string('subject', 256);
		    $t->text('content');
		    $t->string('from_email', 128);
		    $t->string('from_name', 128);
		    $t->string('status', 8);
		    $t->string('campaign_code', 32);
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
	    Schema::table('mt_campaign', function ($t) {
	        $t->dropForeign('mt_campaign_user_id_foreign');
	    });
		Schema::drop('mt_campaign');
	}

}
