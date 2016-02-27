<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewCalendersChecklistCompliancediary extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('old_check_list_actions');
		Schema::drop('old_check_list_tasks');
		Schema::drop('old_check_list_sections');
		Schema::drop('old_events');
		Schema::drop('old_notifications');
		Schema::drop('new_cleaning_schedules_items');
		Schema::rename('cleaning_schedules', 'old_cleaning_schedules');
		Schema::rename('cleaning_schedules_log', 'old_cleaning_schedules_log');
		Schema::rename('health_questionnaires', 'old_health_questionnaires');
		Schema::rename('check_list_items', 'old_check_list_items');
		Schema::rename('check_list_tasks', 'old_check_list_tasks');
		Schema::rename('check_list_submitted', 'old_check_list_submitted');
		Schema::rename('compliance_diary', 'old_compliance_diary');
		Schema::rename('new_compliance_diary_tasks', 'old_new_compliance_diary_tasks');
		Schema::rename('new_compliance_diary_items', 'old_new_compliance_diary_items');
		Schema::rename('new_cleaning_schedules_tasks', 'old_new_cleaning_schedules_tasks');

		Schema::table('old_check_list_tasks', function ($table)
		{
			$table->dropForeign('check_list_tasks_form_id_foreign');
			$table->dropForeign('check_list_tasks_staff_id_foreign');
			$table->dropForeign('check_list_tasks_unit_id_foreign');
		});
		Schema::table('old_check_list_submitted', function ($table)
		{
			$table->dropForeign('check_list_submitted_form_answer_id_foreign');
			$table->dropForeign('check_list_submitted_unit_id_foreign');
		});
		Schema::table('old_check_list_items', function ($table)
		{
			$table->dropForeign('check_list_items_task_id_foreign');
			$table->dropForeign('check_list_items_unit_id_foreign');
		});
		Schema::rename('new_cleaning_schedules_tasks2', 'cleaning_schedules_tasks');
		Schema::rename('new_cleaning_schedules_submitted', 'cleaning_schedules_submitted');
		Schema::create('check_list_tasks', function($table)
		{
			$table->engine = 'InnoDB';
			$table->increments  ('id');
			$table->integer     ('unit_id')->unsigned();
			$table->foreign     ('unit_id')->references('id')->on('units')->onDelete('cascade');
			$table->integer     ('staff_id')->unsigned()->nullable()->default(NULL);
			$table->foreign     ('staff_id')->references('id')->on('staffs')->onDelete('set NULL');
			$table->integer     ('form_id')->unsigned()->nullable()->default(NULL);
			$table->foreign     ('form_id')->references('id')->on('forms')->onDelete('set NULL');
			$table->string      ('title');
			$table->text        ('description')->nullable()->default(NULL);
			$table->string 		('all_day',5)->nullable()->default(NULL);
			$table->datetime    ('start')->nullable()->default(NULL);
			$table->datetime    ('end') ->nullable()->default(NULL);
			$table->string 		('is_repeatable',5)->nullable()->default(NULL);
			$table->string      ('repeat',10)->nullable()->default(NULL);
			$table->TinyInteger ('repeat_every')->nullable()->default(NULL);
			$table->datetime    ('repeat_until')->nullable()->default(NULL);
			$table->string 		('weekends',5)->nullable()->default(NULL);
			$table->string 		('tz',30)->nullable()->default(NULL);
			$table->TinyInteger ('status')->default(0);
			$table->string		('task_color')->nullable()->default(NULL);
			$table->timestamps();
		});
		Schema::create('check_list_items', function($table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->integer('task_id')->unsigned();
			$table->foreign('task_id')->references('id')->on('check_list_tasks')->onDelete('cascade');
			$table->integer('unit_id')->unsigned();
			$table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
			$table->datetime('start')->nullable()->default(NULL);
			$table->datetime('end')->nullable()->default(NULL);
		});
		Schema::create('check_list_submitted', function($table)
		{
			$table->engine = 'InnoDB';
			$table->increments  ('id');
			$table->integer     ('unit_id')->unsigned();
			$table->foreign     ('unit_id')->references('id')->on('units')->onDelete('cascade');
			$table->integer		('task_id')->unsigned()->nullable()->default(NULL);
			$table->foreign		('task_id')->references('id')->on('check_list_tasks')->onDelete('set NULL');
			$table->integer     ('task_item_id')->unsigned()->nullable()->default(NULL);
			$table->foreign		('task_item_id')->references('id')->on('check_list_items')->onDelete('set NULL');
			$table->string      ('title');
			$table->text        ('description')->nullable()->default(NULL);
			$table->string      ('staff_name')->nullable()->default(NULL);
			$table->string      ('form_name')->nullable()->default(NULL);
			$table->integer     ('form_answer_id')->unsigned()->nullable()->default(NULL);
			$table->foreign     ('form_answer_id')->references('id')->on('forms_answers')->onDelete('set NULL');
			$table->datetime	('start')->nullable()->default(NULL);
			$table->datetime	('end')->nullable()->default(NULL);
			$table->TinyInteger ('all_day')->default(1);
			$table->text        ('expired_dates')->nullable()->default(NULL);
			$table->string 		('tz',30)->nullable()->default(NULL);
			$table->text        ('summary')->nullable()->default(NULL);
			$table->TinyInteger ('completed')->default(0);
			$table->string		('task_color')->nullable()->default(NULL);
			$table->timestamps();
		});
		Schema::create('compliance_diary_tasks', function($table)
		{
			$table->engine = 'InnoDB';
			$table->increments  ('id');
			$table->integer     ('unit_id')->unsigned();
			$table->foreign     ('unit_id')->references('id')->on('units')->onDelete('cascade');
			$table->integer     ('staff_id')->unsigned()->nullable()->default(NULL);
			$table->foreign     ('staff_id')->references('id')->on('staffs')->onDelete('set NULL');
			$table->integer     ('form_id')->unsigned()->nullable()->default(NULL);
			$table->foreign     ('form_id')->references('id')->on('forms')->onDelete('set NULL');
			$table->string      ('title');
			$table->text        ('description')->nullable()->default(NULL);
			$table->string 		('all_day',5)->nullable()->default(NULL);
			$table->datetime    ('start')->nullable()->default(NULL);
			$table->datetime    ('end') ->nullable()->default(NULL);
			$table->string 		('is_repeatable',5)->nullable()->default(NULL);
			$table->string      ('repeat',10)->nullable()->default(NULL);
			$table->TinyInteger ('repeat_every')->nullable()->default(NULL);
			$table->datetime    ('repeat_until')->nullable()->default(NULL);
			$table->string 		('weekends',5)->nullable()->default(NULL);
			$table->string 		('tz',30)->nullable()->default(NULL);
			$table->TinyInteger ('status')->default(0);
			$table->string		('task_color')->nullable()->default(NULL);
			$table->timestamps();
		});
		Schema::create('compliance_diary_items', function($table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->integer('task_id')->unsigned();
			$table->foreign('task_id')->references('id')->on('compliance_diary_tasks')->onDelete('cascade');
			$table->integer('unit_id')->unsigned();
			$table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
			$table->datetime('start')->nullable()->default(NULL);
			$table->datetime('end')->nullable()->default(NULL);
		});
		Schema::create('compliance_diary_submitted', function($table)
		{
			$table->engine = 'InnoDB';
			$table->increments  ('id');
			$table->integer     ('unit_id')->unsigned();
			$table->foreign     ('unit_id')->references('id')->on('units')->onDelete('cascade');
			$table->integer		('task_id')->unsigned()->nullable()->default(NULL);
			$table->foreign		('task_id')->references('id')->on('compliance_diary_tasks')->onDelete('set NULL');
			$table->integer     ('task_item_id')->unsigned()->nullable()->default(NULL);
			$table->foreign		('task_item_id')->references('id')->on('compliance_diary_items')->onDelete('set NULL');
			$table->string      ('title');
			$table->text        ('description')->nullable()->default(NULL);
			$table->string      ('staff_name')->nullable()->default(NULL);
			$table->string      ('form_name')->nullable()->default(NULL);
			$table->integer     ('form_answer_id')->unsigned()->nullable()->default(NULL);
			$table->foreign     ('form_answer_id')->references('id')->on('forms_answers')->onDelete('cascade');
			$table->datetime	('start')->nullable()->default(NULL);
			$table->datetime	('end')->nullable()->default(NULL);
			$table->TinyInteger ('all_day')->default(1);
			$table->text        ('expired_dates')->nullable()->default(NULL);
			$table->string 		('tz',30)->nullable()->default(NULL);
			$table->text        ('summary')->nullable()->default(NULL);
			$table->TinyInteger ('completed')->default(0);
			$table->string		('task_color')->nullable()->default(NULL);
			$table->timestamps();
		});

		Schema::table('cleaning_schedules_tasks', function ($table)
		{
			$table->dropForeign('new_cleaning_schedules_tasks2_form_id_foreign');
			$table->dropForeign('new_cleaning_schedules_tasks2_staff_id_foreign');
			$table->dropForeign('new_cleaning_schedules_tasks2_unit_id_foreign');

			$table->dropIndex('new_cleaning_schedules_tasks2_form_id_foreign');
			$table->dropIndex('new_cleaning_schedules_tasks2_staff_id_foreign');
			$table->dropIndex('new_cleaning_schedules_tasks2_unit_id_foreign');
		});
		Schema::table('cleaning_schedules_submitted', function ($table)
		{
			$table->dropForeign('new_cleaning_schedules_submitted_unit_id_foreign');
			$table->dropForeign('new_cleaning_schedules_submitted_form_answer_id_foreign');
			$table->dropForeign('new_cleaning_schedules_submitted_task_id_foreign');
			$table->dropForeign('new_cleaning_schedules_submitted_task_item_id_foreign');
			$table->dropIndex('new_cleaning_schedules_submitted_unit_id_foreign');
			$table->dropIndex('new_cleaning_schedules_submitted_form_answer_id_foreign');
			$table->dropIndex('new_cleaning_schedules_submitted_task_id_foreign');
			$table->dropIndex('new_cleaning_schedules_submitted_task_item_id_foreign');
		});

		Schema::drop('new_cleaning_schedules_items2');

		Schema::create('cleaning_schedules_items', function($table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->integer('task_id')->unsigned();
			$table->foreign('task_id')->references('id')->on('cleaning_schedules_tasks')->onDelete('cascade');
			$table->integer('unit_id')->unsigned();
			$table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
			$table->datetime('start')->nullable()->default(NULL);
			$table->datetime('end')->nullable()->default(NULL);
		});

		Schema::table('cleaning_schedules_tasks', function ($table)
		{
			$table->foreign('form_id')->references('id')->on('forms')->onDelete('set null');
			$table->foreign('staff_id')->references('id')->on('staffs')->onDelete('set null');
			$table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
		});
		Schema::table('cleaning_schedules_submitted', function ($table)
		{
			$table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
			$table->foreign('form_answer_id')->references('id')->on('forms_answers')->onDelete('set null');
			$table->foreign('task_id')->references('id')->on('cleaning_schedules_tasks')->onDelete('set null');
			$table->foreign('task_item_id')->references('id')->on('cleaning_schedules_items')->onDelete('set null');
		});

		$tasks1 = DB::table('old_check_list_tasks')->get();
		if($tasks1)
		foreach($tasks1 as $task){
			$tz = (($task->tz && ($task->tz !== 'UTC')) ? $task->tz : 'Europe/London');
			$start = Carbon::parse($task->start, 'UTC')->timezone($tz)->startOfDay();
			$end = $start->copy()->endOfDay();
			$task_old = [
				'unit_id'=>$task->unit_id,
				'staff_id'=>$task->staff_id,
				'form_id'=>$task->form_id,
				'title'=>$task->title,
				'description'=>$task->description,
				'all_day'=>($task->all_day ? 'on' : null),
				'start'=>$start->copy()->timezone('UTC'),
				'end'=>$end->copy()->timezone('UTC'),
				'is_repeatable'=>(($task->repeat !== 'none') ?  'on' : NULL),
				'repeat'=>(($task->repeat !== 'none') ? $task->repeat : NULL),
				'repeat_every'=>($task->repeat_freq ? : NULL),
				'repeat_until'=>NULL,
				'weekends'=>($task->weekend ? 'on' : NULL),
				'tz'=>$tz,
				'status'=>$task->status,
				'task_color'=>'#007FFF',
			];
			$new_task1 = \Model\CheckListTasks::firstOrCreate($task_old);
			if($new_task1){
				\Services\CheckList::createTasksItems($new_task1);
				$items = DB::table('old_check_list_items')->where('task_id', $task->id)->get();
				if ($items) {
					foreach ($items as $item) {
						$start = Carbon::parse($item->start, 'UTC')->timezone($tz)->startOfDay();
						$end = $start->copy()->endOfDay();
						$submitteds = DB::table('old_check_list_submitted')->where('task_item_id', $item->id)->get();
						if ($submitteds) {
							foreach ($submitteds as $submitted) {
								$submitted_data = [
									'unit_id' => $submitted->unit_id,
									'task_id' => $new_task1->id,
									'title' => $submitted->title,
									'description' => $submitted->description,
									'staff_name' => $submitted->staff_name,
									'form_name' => $submitted->form_name,
									'form_answer_id' => $submitted->form_answer_id,
									'all_day' => $submitted->all_day,
									'summary' => $submitted->summary,
									'completed' => $submitted->completed,
									'start' => $start->copy()->timezone('UTC'),
									'end' => $end->copy()->timezone('UTC'),
									'expired_dates' => NULL,
									'tz' => $tz,
									'task_color' => '#007FFF',
									'created_at' => $submitted->created_at,
									'updated_at' => $submitted->updated_at,
								];
								\Model\CheckListSubmitted::firstOrCreate($submitted_data);
							}
						}
					}
				}
			}
		}
		$tasks2 = DB::table('old_new_compliance_diary_tasks')->get();
		if($tasks2)
		foreach($tasks2 as $task){
			$tz = (($task->tz && ($task->tz !== 'UTC')) ? $task->tz : 'Europe/London');
			$start = Carbon::parse($task->start, 'UTC')->timezone($tz)->startOfDay();
			$end = $start->copy()->endOfDay();
			$task_data = [
				'unit_id'=>$task->unit_id,
				'staff_id'=>$task->staff_id,
				'title'=>$task->title,
				'description'=>$task->description,
				'all_day'=>($task->all_day ? 'on' : null),
				'start'=>$start->copy()->timezone('UTC'),
				'end'=>$end->copy()->timezone('UTC'),
				'is_repeatable'=>(($task->repeat !== 'none') ?  'on' : NULL),
				'repeat'=>(($task->repeat !== 'none') ? $task->repeat : NULL),
				'repeat_every'=>($task->repeat_freq ? : NULL),
				'repeat_until'=>NULL,
				'weekends'=>($task->weekend ? 'on' : NULL),
				'tz'=>$tz,
				'status'=>$task->status,
				'task_color'=>'#007FFF',
			];
			$new_task2 = \Model\ComplianceDiaryTasks::firstOrCreate($task_data);
			\Services\ComplianceDiary::createTasksItems($new_task2);
		}
		DB::table('cleaning_schedules_items')->delete();
		$cleaning_tasks = \Model\CleaningSchedulesTasks::all();
		foreach($cleaning_tasks as $task){
			\Services\CleaningSchedule::createTasksItems($task);
		}
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
