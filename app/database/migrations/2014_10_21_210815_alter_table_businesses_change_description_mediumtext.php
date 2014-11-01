<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableBusinessesChangeDescriptionMediumtext extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        \DB::statement('ALTER TABLE `' . \DB::getTablePrefix() . 'businesses` CHANGE `description` `description` MEDIUMTEXT COLLATE utf8_unicode_ci');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        \DB::statement('ALTER TABLE `' . \DB::getTablePrefix() . 'businesses` CHANGE `description` `description` VARCHAR(255) COLLATE utf8_unicode_ci DEFAULT NULL');
	}

}
