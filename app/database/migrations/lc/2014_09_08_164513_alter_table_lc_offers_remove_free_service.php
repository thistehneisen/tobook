<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableLcOffersRemoveFreeService extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lc_offers', function(Blueprint $table) {
            $table->dropColumn('free_service');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lc_offers', function(Blueprint $table) {
            $table->unsignedInteger('free_service');
        });
	}
}
