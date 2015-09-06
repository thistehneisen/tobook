<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDiscountIncludedToAsServices extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_services', function(Blueprint $table)
        {
            $table->boolean('is_discount_included')->default(true);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('as_services', function(Blueprint $table)
        {
            $table->dropColumn('is_discount_included');
        });
	}

}
