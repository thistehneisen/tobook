<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConsumerIdToUsers extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table)
        {
            $table->unsignedInteger('consumer_id')->nullable();
            $table->foreign('consumer_id')->references('id')->on('consumers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table)
        {
            $table->dropForeign('users_consumer_id_foreign');
            $table->dropColumn('consumer_id');
        });
    }

}
