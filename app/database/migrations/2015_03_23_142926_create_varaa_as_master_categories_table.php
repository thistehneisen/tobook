<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVaraaAsMasterCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_master_categories', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->unsignedInteger('order');
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
		Schema::drop('as_master_categories');
	}

}
