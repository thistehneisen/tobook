<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SwapLengthAndTotalColumnInAsServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_services', function(Blueprint $table)
		{
			DB::statement("UPDATE `varaa_as_services` SET `length` = `during`, `during` = @oldlength WHERE (@oldlength := `length`)");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('as_services', function(Blueprint $table)
		{
			//
		});
	}

}
