<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTreatmentTypeIdToAsServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('as_services', function(Blueprint $table)
        {
            $table->unsignedInteger('treatment_type_id')
                ->after('master_category_id')
                ->nullable();
            $table->foreign('treatment_type_id')
                ->references('id')
                ->on('as_treatment_types')
                ->onDelete('set null');
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
