<?php

use Illuminate\Database\Migrations\Migration;

class AlterTableUsersChangeUsernameNullable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        \DB::statement('ALTER TABLE `' . \DB::getTablePrefix() . 'users` CHANGE `username` `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        \DB::statement('ALTER TABLE `' . \DB::getTablePrefix() . 'users` CHANGE `username` `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL');
	}

}
