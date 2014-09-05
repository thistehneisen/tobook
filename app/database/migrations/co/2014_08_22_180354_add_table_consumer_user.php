<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableConsumerUser extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumer_user', function(Blueprint $table)
        {
            $table->unsignedInteger('consumer_id');
            $table->unsignedInteger('user_id');
            $table->boolean('is_visible');
            $table->primary(['consumer_id', 'user_id']);
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('consumer_id')
                ->references('id')
                ->on('consumers')
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
        Schema::table('consumer_user', function($table) {
            $table->dropForeign('consumer_user_user_id_foreign');
            $table->dropForeign('consumer_user_consumer_id_foreign');
        });
        Schema::drop('consumer_user');
    }

}
