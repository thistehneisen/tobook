<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableFdFlashDealDates extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fd_flash_deal_dates', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('flash_deal_id');
            $table->date('date');
            $table->time('time');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('flash_deal_id')
                ->references('id')
                ->on('fd_flash_deals')
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
        Schema::table('fd_flash_deal_dates', function ($table) {
            $table->dropForeign('fd_flash_deal_dates_flash_deal_id_foreign');
        });
        Schema::drop('fd_flash_deal_dates');
    }

}
