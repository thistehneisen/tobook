<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConsumerStatusToBusinessCommisionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('business_commissions', function(Blueprint $table)
        {
            $table->text('consumer_status');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

        Schema::table('business_commissions', function(Blueprint $table)
        {
            $table->dropColumn('consumer_status');
        });
	}

}
