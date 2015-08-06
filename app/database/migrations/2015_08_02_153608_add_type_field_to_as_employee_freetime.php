<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeFieldToAsEmployeeFreetime extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_employee_freetime', function(Blueprint $table)
        {
            $table->tinyInteger('type')->default(1)->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('as_employee_freetime', function(Blueprint $table)
        {
            $table->dropColumn('type');
        });
	}

}
