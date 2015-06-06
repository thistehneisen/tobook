<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUniqueKeyForKeywordTreatmentTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_keyword_treatment_type', function(Blueprint $table)
        {
            $table->dropUnique('as_keyword_treatment_type_keyword_treatment_type_id_unique');
            $table->unique(['keyword']);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
