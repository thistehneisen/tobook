<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVaraaCouponsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_coupons', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('code');
            $table->unsignedInteger('campaign_id');
            $table->boolean('is_used')->default(false);
            $table->foreign('campaign_id')
                ->references('id')
                ->on('as_coupon_campaigns')
                ->onDelete('cascade');
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
		Schema::drop('as_coupons');
	}

}
