<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsServiceTimesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_service_times', function(Blueprint $table)
		{
            $table->increments('id');
            $table->unsignedInteger('service_id');
            $table->double('price');
            $table->tinyInteger('length');
            $table->tinyInteger('before');
            $table->tinyInteger('during');
            $table->tinyInteger('after');
            $table->string('description');
            $table->foreign('service_id')->references('id')->on('as_services')->onDelete('cascade');
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
		Schema::drop('as_service_times');
	}

}
