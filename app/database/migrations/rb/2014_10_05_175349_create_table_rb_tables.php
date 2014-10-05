<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRbTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rb_tables', function(Blueprint $table)
		{
			$table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('name');
            $table->float('width')->nullable();
            $table->float('height')->nullable();
            $table->float('left')->nullable();
            $table->float('right')->nullable();
            $table->unsignedInteger('seats');
            $table->unsignedInteger('minimum');
            $table->timestamps();
            $table->softDeletes();
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
		Schema::drop('rb_tables');
	}

}
