<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsFlashDealServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('as_flash_deal_services', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('flash_deal_id')->nullable();
            $table->unsignedInteger('service_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('service_id')
                ->references('id')
                ->on('as_services')
                ->onDelete('cascade');
            $table->foreign('flash_deal_id')
                ->references('id')
                ->on('as_flash_deals')
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
		//
	}

}
