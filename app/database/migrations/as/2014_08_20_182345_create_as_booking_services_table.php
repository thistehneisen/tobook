<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsBookingServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_booking_services', function(Blueprint $table)
		{
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('booking_id');
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('service_time_id')->nullable();
            $table->unsignedInteger('employee_id');
            $table->date('date');
            $table->time('start_at');
            $table->time('end_at');
            $table->boolean('is_reminder_email');
            $table->boolean('is_reminder_sms');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('service_id')
                ->references('id')
                ->on('as_services')
                ->onDelete('cascade');
            $table->foreign('service_time_id')
                ->references('id')
                ->on('as_service_times')
                ->onDelete('cascade');
            $table->foreign('employee_id')
                ->references('id')
                ->on('as_employees')
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
		Schema::drop('as_booking_services');
	}

}
