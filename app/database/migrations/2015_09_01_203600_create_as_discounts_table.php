<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsDiscountsTable extends Migration {

	/**
	 * Run the migrations.
	 * @see https://github.com/varaa/varaa/issues/631
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_discounts', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->time('start_at');
            $table->time('end_at');
            $table->enum('weekday', array('mon', 'tue','wed','thu','fri','sat','sun'));
            $table->enum('period', array('morning', 'afternoon', 'evening'));
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
		Schema::drop('as_discounts');
	}

}
