<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldStatusToAsEmployeesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        //Status for divide employees into 2 or more group (employee|freelancer)
		Schema::table('as_employees', function(Blueprint $table)
        {
            $table->unsignedInteger('status');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('as_employees', function(Blueprint $table)
        {
            $table->dropColumn('as_employees');
        });
	}

}
