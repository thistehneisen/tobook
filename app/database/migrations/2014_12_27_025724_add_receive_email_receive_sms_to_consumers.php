<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReceiveEmailReceiveSmsToConsumers extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consumers', function ($t) {
            $t->string('receive_email')->after('country')->default(true);
            $t->string('receive_sms')->after('receive_email')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consumers', function ($t) {
            $t->dropColumn('receive_email');
            $t->dropColumn('receive_sms');
        });
    }

}
