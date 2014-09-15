<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnIsDayOffFromEmployeeCustomTimeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_employee_custom_time', function(Blueprint $table)
        {
            $table->dropColumn('start_at');
            $table->dropColumn('end_at');
            $table->dropColumn('is_day_off');
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
