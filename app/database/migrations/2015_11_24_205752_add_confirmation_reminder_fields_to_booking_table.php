<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConfirmationReminderFieldsToBookingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_bookings', function(Blueprint $table)
        {
            $table->boolean('is_reminder_sms')->after('ip')->default(0);
            $table->datetime('reminder_sms_at')->after('is_reminder_sms');
            $table->boolean('is_reminder_email')->after('reminder_sms_at')->default(0);
            $table->datetime('reminder_email_at')->after('is_reminder_email');
            $table->boolean('is_confirmation_email')->after('reminder_email_at')->default(1);
            $table->boolean('is_confirmation_sms')->after('is_confirmation_email')->default(1);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('as_bookings', function (Blueprint $table) {
            $table->dropColumn([
            	'is_reminder_sms', 
            	'reminder_sms_at', 
            	'is_reminder_email', 
            	'reminder_email_at',
            	'is_confirmation_email',
            	'is_confirmation_sms'
            ]);
        });
	}

}
