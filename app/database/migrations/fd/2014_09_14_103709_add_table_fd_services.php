<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableFdServices extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fd_services', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('name');
            $table->double('price');
            $table->text('description');
            $table->mediumInteger('quantity');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::table('fd_services', function ($table) {
            $table->dropForeign('fd_services_user_id_foreign');
        });
        Schema::drop('fd_services');
    }

}
