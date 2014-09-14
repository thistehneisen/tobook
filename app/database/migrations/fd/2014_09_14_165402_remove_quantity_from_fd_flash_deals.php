<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveQuantityFromFdFlashDeals extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fd_flash_deal_dates', function(Blueprint $table)
        {
            $table->mediumInteger('remains');
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
            $table->dropColumn('remains');
        });
    }

}
