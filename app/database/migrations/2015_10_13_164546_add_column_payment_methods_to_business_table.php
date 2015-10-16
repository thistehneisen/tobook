<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPaymentMethodsToBusinessTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Only use for tobook
		Schema::table('businesses', function(Blueprint $table)
        {
            $table->string('payment_methods')->after('payment_options')->default('');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('businesses', function(Blueprint $table)
        {
            $table->dropColumn('payment_methods');
        });
	}

}
