<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLcTransactions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lc_transactions', function(Blueprint $table)
		{
			$table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('consumer_id');
            $table->unsignedInteger('voucher_id')->nullable();
            $table->unsignedInteger('offer_id')->nullable();
            $table->integer('point')->nullable();
            $table->integer('stamp')->nullable();
            $table->integer('free_service')->nullable();
            $table->timestamps();
            $table->foreign('consumer_id')
                ->references('id')
                ->on('lc_consumers')
                ->onDelete('cascade');
            $table->foreign('voucher_id')
                ->references('id')
                ->on('lc_vouchers')
                ->onDelete('cascade');
            $table->foreign('offer_id')
                ->references('id')
                ->on('lc_offers')
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
		Schema::drop('lc_transactions');
	}

}
