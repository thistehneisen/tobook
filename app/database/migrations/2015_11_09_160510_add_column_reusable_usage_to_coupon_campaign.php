<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnReusableUsageToCouponCampaign extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_coupon_campaigns', function(Blueprint $table)
        {
            $table->integer('reusable_usage')
            	->after('reusable_code')->default(0);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('as_coupon_campaigns', function(Blueprint $table)
        {
            $table->dropColumn('reusable_usage');
        });
	}

}
