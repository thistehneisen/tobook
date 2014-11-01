<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateToFdFlashDealDates extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fd_flash_deal_dates', function(Blueprint $table)
        {
            $table->dateTime('expire')->after('flash_deal_id');
            $table->unique(['flash_deal_id', 'expire']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fd_flash_deal_dates', function(Blueprint $table)
        {
            $table->dropColumn('expire');
        });
    }

}
