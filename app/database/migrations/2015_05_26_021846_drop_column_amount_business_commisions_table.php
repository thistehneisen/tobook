<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnAmountBusinessCommisionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('business_commissions', function (Blueprint $table) {
            $table->dropColumn('amount');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

        Schema::table('business_commissions', function (Blueprint $table) {
            $table->double('amount')->default(0);
        });
	}

}
