<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsResourceServiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_resource_service', function(Blueprint $table)
		{
            $table->increments('id');
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('resource_id');
            $table->foreign('service_id')
                ->references('id')
                ->on('as_services')
                ->onDelete('cascade');
            $table->foreign('resource_id')
                ->references('id')
                ->on('as_resources')
                ->onDelete('cascade');
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
		Schema::drop('as_resource_service');
	}

}
