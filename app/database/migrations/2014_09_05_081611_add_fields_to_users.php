<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUsers extends Migration
{
    /**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('description');
            $table->string('business_size');
        });
    }

    /**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['description', 'business_size']);
        });
    }

}
