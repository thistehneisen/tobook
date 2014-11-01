<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveCustomTimeIdColumnAfterEmployeeIdInEmployeeCustomTimeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE `varaa_as_employee_custom_time` CHANGE `custom_time_id` `custom_time_id` int(10) unsigned NULL AFTER `employee_id`;");
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
