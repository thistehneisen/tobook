<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveFieldsInTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'fax',
                'stylesheet',
                'address_2',
                'state'
            ]);
            $table->renameColumn('address_1', 'address');
            $table->renameColumn('zipcode', 'postcode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('fax');
            $table->string('stylesheet');
            $table->string('address_2');
            $table->string('state');
            $table->renameColumn('address', 'address_1');
            $table->renameColumn('postcode', 'zipcode');
        });
    }

}
