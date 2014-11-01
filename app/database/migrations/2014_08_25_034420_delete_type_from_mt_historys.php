<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteTypeFromMtHistorys extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    Schema::table('mt_historys', function ($t) {
	        $t->dropColumn('type');
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
	    Schema::table('mt_historys', function ($t) {
	        $t->string('type', 8)->after('id');
	    });
	}

}
