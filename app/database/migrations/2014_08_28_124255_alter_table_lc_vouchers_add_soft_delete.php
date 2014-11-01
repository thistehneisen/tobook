<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableLcVouchersAddSoftDelete extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lc_vouchers', function(Blueprint $table)
		{
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lc_vouchers', function(Blueprint $table)
		{
			$table->dropSoftDeletes();
		});
	}

}
