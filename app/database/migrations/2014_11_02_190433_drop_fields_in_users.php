<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropFieldsInUsers extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($t) {
            $t->dropColumn('first_name');
            $t->dropColumn('last_name');
            $t->dropColumn('address');
            $t->dropColumn('city');
            $t->dropColumn('postcode');
            $t->dropColumn('country');
            $t->dropColumn('phone');
            $t->dropColumn('business_name');
            $t->dropColumn('description');
            $t->dropColumn('business_size');
            $t->dropColumn('lat');
            $t->dropColumn('lng');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
