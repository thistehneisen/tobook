<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsLastMinuteDiscountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_last_minute_discounts', function(Blueprint $table)
        {
            $table->unsignedInteger('user_id');
            $table->boolean('is_active');
            $table->tinyInteger('before');
            $table->tinyInteger('discount');
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
		Schema::drop('as_last_minute_discounts');
	}

}
