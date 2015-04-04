<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusFieldToAsFlashDeals extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('as_flash_deals', function (Blueprint $table) {
           $table->tinyInteger('status')->default(1)->after('discount_percentage');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('as_flash_deals', function (Blueprint $table) {
           $table->dropColumn('status')->after('discount_percentage');
        });
	}

}
