<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsumerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::create('consumers', function($t)
	    {
	        $t->increments('id');
	        $t->unsignedInteger('user_id');
	        $t->string('first_name');
	        $t->string('last_name');
	        $t->string('email');
	        $t->string('phone');
	        $t->string('address');
	        $t->string('city');
	        $t->string('postcode');
	        $t->string('country');
	        $t->timestamps();
	        $t->foreign('user_id')
	            ->references('id')
	            ->on('users')
	            ->onDelete('cascade');
	        $t->unique(['user_id', 'email']);
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
	    Schema::table('consumers', function($t)
	    {
	        $t->dropForeign('consumers_user_id_foreign');
	    });
	    Schema::drop('consumers');
	}
}
