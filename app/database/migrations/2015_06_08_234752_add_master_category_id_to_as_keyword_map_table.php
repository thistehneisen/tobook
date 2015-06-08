<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMasterCategoryIdToAsKeywordMapTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_keyword_map', function(Blueprint $table)
        {
            $table->unsignedInteger('master_category_id')->after('treatment_type_id')->nullable();
            $table->foreign('master_category_id')
                ->references('id')
                ->on('as_master_categories')
                ->onDelete('cascade');
        });
        DB::statement('ALTER TABLE `varaa_as_keyword_map` MODIFY `treatment_type_id` INTEGER UNSIGNED NULL;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('as_keyword_map', function(Blueprint $table)
        {
            $table->dropColumn('master_category_id');
        });
	}

}
