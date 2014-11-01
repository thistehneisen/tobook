<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLcOffers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lc_offers', function(Blueprint $table)
		{
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('name');
            $table->unsignedInteger('required');
            $table->unsignedInteger('total_used');
            $table->unsignedInteger('free_service');
            $table->boolean('is_active');
            $table->boolean('is_auto_add');
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('lc_offers');
	}

}
