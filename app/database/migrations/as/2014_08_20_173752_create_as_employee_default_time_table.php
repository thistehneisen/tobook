<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsEmployeeDefaultTimeTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_employee_default_time', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('employee_id');
            $table->enum('type', array('mon', 'tue','wed','thu','fri','sat','sun'));
            $table->time('start_at');
            $table->time('end_at');
            $table->boolean('is_day_off');
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
        Schema::drop('as_employee_default_time');
    }

}
