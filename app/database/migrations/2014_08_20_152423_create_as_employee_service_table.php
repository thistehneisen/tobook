<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsEmployeesServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_employee_service', function(Blueprint $table)
		{
			$table->unsignedInteger('employee_id');
            $table->unsignedInteger('service_id');
            $table->primary(array('employee_id', 'service_id'));
            $table->tinyInteger('plustime');
            $table->foreign('employee_id')->references('id')->on('as_employees')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('as_services')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('as_employee_service');
	}

}
