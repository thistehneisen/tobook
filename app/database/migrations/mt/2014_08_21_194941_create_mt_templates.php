<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMtTemplates extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('mt_templates', function ($t) {
	        $t->engine ='InnoDB';
	        $t->increments('id')->unsigned();
	        $t->string('name', 64);
	        $t->string('thumbnail', 128);
	        $t->text('content');
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
	    Schema::table('mt_templates', function ($t) {
	        $t->dropForeign('mt_templates_user_id_foreign');
	    });
	    Schema::drop('mt_templates');
	}

}
