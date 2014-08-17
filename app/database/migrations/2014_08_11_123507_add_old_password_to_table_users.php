<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOldPasswordToTableUsers extends Migration
{
    /**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('old_password')->nullable()->after('password');
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
            $table->dropColumn('old_password');
        });
    }

}
