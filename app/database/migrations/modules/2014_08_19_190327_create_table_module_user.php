<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableModuleUser extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_user', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('module_id');
            $table->unsignedInteger('user_id');
            $table->datetime('start');
            $table->datetime('end');
            $table->timestamps();
            $table->foreign('module_id')
                ->references('id')->on('modules')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('users')
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
        Schema::table('module_user', function($table) {
            $table->dropForeign('module_user_module_id_foreign');
            $table->dropForeign('module_user_user_id_foreign');
        });
        Schema::drop('module_user');
    }

}
