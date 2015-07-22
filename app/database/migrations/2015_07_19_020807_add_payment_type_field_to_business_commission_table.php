<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentTypeFieldToBusinessCommissionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('business_commissions', function(Blueprint $table)
        {
            $table->string('payment_type')->nullable();
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
            $table->dropColumn('payment_type');
        });
	}

}
