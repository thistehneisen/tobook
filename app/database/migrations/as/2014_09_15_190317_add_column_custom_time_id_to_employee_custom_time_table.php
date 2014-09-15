<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCustomTimeIdToEmployeeCustomTimeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_employee_custom_time', function(Blueprint $table)
        {
            $table->unsignedInteger('custom_time_id')->after('employee_id');
            $table->foreign('custom_time_id')
                ->references('id')
                ->on('as_custom_times')
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
		//
	}

}
