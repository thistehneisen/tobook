<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsBookingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_bookings', function(Blueprint $table)
		{
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->tinyInteger('total');
            $table->date('date');
            $table->time('start_at');
            $table->string('status');
            $table->string('ip');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('as_bookings');
	}

}
