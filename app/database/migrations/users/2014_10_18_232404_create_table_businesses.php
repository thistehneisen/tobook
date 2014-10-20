<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBusinesses extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('businesses', function(Blueprint $table)
		{
			$table->increments('id');
            $table->unsignedInteger('user_id');
            $table->boolean('is_activated')->default(false);

            $table->string('name');
            $table->string('description');
            $table->string('size');
            $table->string('address');
            $table->string('city');
            $table->string('postcode');
            $table->string('country');
            $table->string('phone');
            $table->double('lat', 10, 6);
            $table->double('lng', 10, 6);

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
		Schema::drop('businesses');
	}

}
