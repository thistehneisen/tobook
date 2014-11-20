<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableMtGroupsAddSoftDeletes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('mt_groups', function(Blueprint $table)
        {
            $table->softDeletes();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('mt_groups', function(Blueprint $table)
        {
            $table->dropSoftDeletes();
        });
	}

}
