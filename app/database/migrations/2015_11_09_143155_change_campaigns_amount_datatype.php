<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCampaignsAmountDatatype extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("ALTER TABLE `varaa_as_coupon_campaigns` MODIFY `amount` INT(11);");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        DB::statement("ALTER TABLE `varaa_as_coupon_campaigns` MODIFY `amount` TINYINT(4);");
	}

}
