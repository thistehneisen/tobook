<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsFlashDealEnabledToAsServiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_services', function(Blueprint $table)
        {
            $table->boolean('is_flash_deal_enabled')->default(false);;
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('as_services', function (Blueprint $table) {
            $table->dropColumn('is_flash_deal_enabled');
        });
	}

}
