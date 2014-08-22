<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetDefaultStatusToMtCampaign extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
	    DB::statement(DB::raw("ALTER TABLE `".DB::getTablePrefix()."mt_campaign` CHANGE COLUMN `status` `status` varchar(8) NOT NULL DEFAULT 'DRAFT';"));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	    DB::statement(DB::raw("ALTER TABLE `".DB::getTablePrefix()."mt_campaign` CHANGE COLUMN `status` `status` varchar(8) NOT NULL;"));
	}

}
