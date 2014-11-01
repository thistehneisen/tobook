<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsServicesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_services', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('user_id');
            $table->string('name');
            $table->double('price');
            $table->tinyInteger('length');
            $table->tinyInteger('before');
            $table->tinyInteger('during');
            $table->tinyInteger('after');
            $table->string('description');
            $table->boolean('is_active');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')->onDelete('cascade');
            $table->foreign('category_id')
                ->references('id')
                ->on('as_service_categories')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('as_services');
    }

}
