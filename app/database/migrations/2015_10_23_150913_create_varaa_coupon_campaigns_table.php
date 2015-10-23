<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVaraaCouponCampaignsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_coupon_campaigns', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->datetime('expire_at');
            $table->datetime('begin_at');
            $table->integer('discount');
            $table->string('discount_type');
            $table->string('reusable_code');//for reusable campaign type
            $table->tinyInteger('amount');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('as_coupon_campaigns');
	}

}
