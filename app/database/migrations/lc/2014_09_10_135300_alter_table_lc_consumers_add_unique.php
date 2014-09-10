<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableLcConsumersAddUnique extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lc_consumers', function(Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unique(['consumer_id', 'user_id']);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lc_consumers', function(Blueprint $table) {
            $table->dropUnique(['consumer_id', 'user_id']);
            $table->dropColumn('user_id');
        });
	}

}
