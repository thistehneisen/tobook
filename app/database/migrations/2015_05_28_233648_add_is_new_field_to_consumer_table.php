<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsNewFieldToConsumerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('consumers', function(Blueprint $table)
        {
            $table->boolean('is_new')->default(true);
        });

        DB::statement('UPDATE varaa_as_consumers SET is_new = FALSE');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('consumers', function(Blueprint $table)
        {
            $table->dropColumn('is_new');
        });
	}

}
