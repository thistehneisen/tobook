<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDisabledModules extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disabled_modules', function(Blueprint $table)
        {
            $table->unsignedInteger('user_id');
            $table->string('module');
            $table->timestamps();
            $table->primary(['user_id', 'module']);
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
        Schema::table('disabled_modules', function($table) {
            $table->dropForeign('disabled_modules_user_id_foreign');
        });
        Schema::drop('disabled_modules');
    }

}
