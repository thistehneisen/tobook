<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreCommissionTypeFieldToBusinessCommisionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('business_commissions', function(Blueprint $table)
        {
            $table->double('commission')->default(0);
            $table->double('constant_commission')->default(0);
            $table->double('new_consumer_commission')->default(0);
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
            $table->dropColumn('commission');
            $table->dropColumn('constant_commission');
            $table->dropColumn('new_consumer_commission');
        });
	}

}
