<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLcPointTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lc_point', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('owner');
            $table->string('name', 50);
            $table->integer('score');
            $table->integer('discount');
            $table->tinyInteger('valid')->default(0);
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
		Schema::drop('lc_point');
	}

}
