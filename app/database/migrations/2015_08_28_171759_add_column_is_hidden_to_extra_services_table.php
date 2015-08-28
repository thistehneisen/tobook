<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsHiddenToExtraServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_extra_service_service', function(Blueprint $table)
        {
            $table->boolean('is_hidden')->default(false);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('as_extra_service_service', function(Blueprint $table)
        {
            $table->dropColumn('is_hidden');
        });
	}

}
