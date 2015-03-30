<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateAndStartTimeToAsFlashDealsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_flash_deals', function(Blueprint $table)
        {
            $table->date('date')->after('discount_percentage');
            $table->time('start_at')->after('date');
            $table->time('end_at')->after('start_at');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('as_flash_deals', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dropColumn('start_at');
            $table->dropColumn('end_at');
        });
	}

}
