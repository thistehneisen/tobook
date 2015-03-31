<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmployeeIdToFlashDealsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_flash_deals', function(Blueprint $table)
        {
            $table->unsignedInteger('employee_id')->after('user_id');
            $table->foreign('employee_id')
                ->references('id')
                ->on('as_employees')
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
		Schema::table('as_flash_deals', function ($table) {
            $table->dropForeign('as_flash_deals_employee_id_foreign');
        });

        Schema::table('as_flash_deals', function(Blueprint $table) {
            $table->dropColumn('employee_id');
        });
	}

}
