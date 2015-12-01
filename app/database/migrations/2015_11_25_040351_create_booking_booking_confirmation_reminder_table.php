<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingBookingConfirmationReminderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_booking_confirmation_reminders', function(Blueprint $table)
        {
            $table->unsignedInteger('booking_id');
            $table->boolean('is_reminder_sms')->default(0);
            $table->integer('reminder_sms_before');
            $table->datetime('reminder_sms_at');
            $table->boolean('is_reminder_email')->default(0);
            $table->integer('reminder_email_before');
            $table->datetime('reminder_email_at');
            $table->string('reminder_email_time_unit');
            $table->string('reminder_sms_time_unit');
            $table->boolean('is_confirmation_email')->default(1);
            $table->boolean('is_confirmation_sms')->default(1);
            $table->primary('booking_id'); 
            $table->foreign('booking_id')
                ->references('id')
                ->on('as_bookings')
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
		Schema::drop('as_booking_confirmation_reminders');
	}

}
