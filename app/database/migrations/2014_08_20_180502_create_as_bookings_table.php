<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsBookingsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_bookings', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('uuid');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('consumer_id');
            $table->unsignedInteger('employee_id');
            $table->date('date');
            $table->tinyInteger('total');
            $table->tinyInteger('modify_time');
            $table->time('start_at');
            $table->time('end_at');
            $table->tinyInteger('status');
            $table->string('ip');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('consumer_id')
                ->references('id')
                ->on('consumers')
                ->onDelete('cascade');
            $table->foreign('employee_id')
                ->references('id')
                ->on('as_employees')
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
        Schema::drop('as_bookings');
    }

}
