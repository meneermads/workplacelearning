<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToWorkplacelearningperiodTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('workplacelearningperiod', function(Blueprint $table)
		{
			$table->foreign('student_id', 'fk_WorkplaceLearningPeriod_Student1')->references('student_id')->on('student')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('wp_id', 'fk_WorkplaceLearningPeriod_Workplace1')->references('wp_id')->on('workplace')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('workplacelearningperiod', function(Blueprint $table)
		{
			$table->dropForeign('fk_WorkplaceLearningPeriod_Student1');
			$table->dropForeign('fk_WorkplaceLearningPeriod_Workplace1');
		});
	}

}
