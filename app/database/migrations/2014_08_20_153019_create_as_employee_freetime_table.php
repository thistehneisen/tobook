
    <?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsEmployeeFreetimeTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_employee_freetime', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('employee_id');
            $table->date('date');
            $table->time('start_at');
            $table->time('end_at');
            $table->string('description');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('employee_id')
                ->references('id')
                ->on('as_employees')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('as_employee_freetime');
    }

}
