<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepositRateAndEmployeeToCommissionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('business_commissions', function(Blueprint $table)
        {
            $table->double('deposit_rate');
            $table->double('total_price');// booking total price
            $table->unsignedInteger('employee_id')
                ->after('booking_id');
            $table->foreign('employee_id')
                ->references('id')
                ->on('as_employees');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('business_commissions', function(Blueprint $table)
        {
            $table->dropForeign('business_commissions_employee_id_foreign');
            $table->dropColumn(['deposit_rate', 'total_price', 'employee_id']);
        });
	}

}
