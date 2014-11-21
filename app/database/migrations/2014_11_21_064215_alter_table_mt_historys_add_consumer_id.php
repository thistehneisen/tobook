<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterTableMtHistorysAddConsumerId extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mt_historys', function (Blueprint $table) {
            $table->unsignedInteger('consumer_id')->nullable();
            $table->foreign('consumer_id')->references('id')->on('consumers');
        });

        DB::statement('ALTER TABLE `' . DB::getTablePrefix() . 'mt_historys` MODIFY COLUMN group_id INT(10) UNSIGNED DEFAULT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mt_historys', function (Blueprint $table) {
            $table->dropForeign('mt_historys_consumer_id_foreign');
            $table->dropColumn('consumer_id');
        });
    }

}
