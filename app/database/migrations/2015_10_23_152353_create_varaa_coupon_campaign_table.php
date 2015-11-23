<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVaraaCouponCampaignTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_coupon_campaign', function(Blueprint $table)
        {
            $table->unsignedInteger('campaign_id');
            $table->unsignedInteger('coupon_id');
            $table->primary(array('campaign_id', 'coupon_id'));
            $table->foreign('coupon_id')
                ->references('id')
                ->on('as_coupons')
                ->onDelete('cascade');
            $table->foreign('campaign_id')
                ->references('id')
                ->on('as_coupon_campaigns')
                ->onDelete('cascade');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('as_coupon_campaign');
	}

}
