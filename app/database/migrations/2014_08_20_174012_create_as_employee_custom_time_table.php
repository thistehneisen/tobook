<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsEmployeeCustomTimeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_employee_custom_time', function(Blueprint $table)
		{
            $table->increments('id');
            $table->unsignedInteger('employee_id');
            $table->date('date');
            $table->time('start_at');
            $table->time('end_at');
            $table->time('start_lunch_at');
            $table->time('end_lunch_at');
            $table->boolean('is_day_off');
            $table->foreign('employee_id')->references('id')->on('as_employees')->onDelete('cascade');
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
		Schema::drop('as_employee_custom_time');
	}

}
