<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsReceivedCalendarInvitationToEmployeeTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('as_employees', function(Blueprint $table)
        {
            $table->boolean('is_received_calendar_invitation')
                ->after('is_subscribed_sms')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('as_employees', function ($t) {
            $t->dropColumn('is_received_calendar_invitation');
        });
    }

}
