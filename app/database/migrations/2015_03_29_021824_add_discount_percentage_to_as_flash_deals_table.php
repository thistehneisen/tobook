<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiscountPercentageToAsFlashDealsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_flash_deals', function(Blueprint $table)
        {
            $table->double('discount_percentage')->after('discounted_price');
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
            $table->dropColumn('discount_percentage');
        });
	}

}
