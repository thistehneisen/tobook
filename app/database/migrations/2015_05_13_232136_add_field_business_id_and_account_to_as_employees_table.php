<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldBusinessIdAndAccountToAsEmployeesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_employees', function(Blueprint $table)
        {
            $table->text('business_id');
            $table->text('account');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('as_employees', function(Blueprint $table)
        {
            $table->dropColumn(['business_id', 'account']);
        });
	}

}
