<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewCleaningSchedulesItems2 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$oldItems = \DB::table('new_cleaning_schedules_items')->where('end','<',\Carbon::now())->get();
		foreach($oldItems as $oldItem){
			$oldSubm = \DB::table('new_cleaning_schedules_submitted')->where('task_item_id',$oldItem->id)->first();
			if($oldSubm){
				\DB::table('new_cleaning_schedules_items')->delete($oldItem->id);
			}
		}
		Schema::create('new_cleaning_schedules_tasks2', function($table)
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
			$table->timestamps();
		});
		Schema::create('new_cleaning_schedules_items2', function($table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->integer('task_id')->unsigned();
			$table->foreign('task_id')->references('id')->on('new_cleaning_schedules_tasks2')->onDelete('cascade');
			$table->integer('unit_id')->unsigned();
			$table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
			$table->datetime('start')->nullable()->default(NULL);
			$table->datetime('end')->nullable()->default(NULL);
		});

		$items = \Model\NewCleaningSchedulesSubmitted::all();
		Schema::table('new_cleaning_schedules_submitted', function($table)
		{
			$table->string('tz',30)->nullable()->default(NULL);
			$table->datetime('start1')->nullable()->default(NULL);
			$table->datetime('end1')->nullable()->default(NULL);
		});
		foreach($items as $item){
			$tz = 'Europe/London';
			$start = \Carbon::parse($item->start, 'UTC')->timezone($tz)->setTime(0,0,0);
			$end = \Carbon::parse($item->end, 'UTC')->timezone($tz)->setTime(23,59,59);
			$item->start1 = $start->timezone('UTC');
			$item->end1 = $end->timezone('UTC');
			$item->tz = $tz;
			$item->update();
		}
		Schema::table('new_cleaning_schedules_submitted', function($table)
		{
			$table->dropColumn('start');
			$table->dropColumn('end');
			$table->renameColumn('start1', 'start');
			$table->renameColumn('end1', 'end');
			$table->integer('task_id')->nullable()->default(NULL);
			$table->text('expired_dates')->nullable()->default(NULL);
		});

		$tasks = DB::table('new_cleaning_schedules_tasks')->get();

		foreach ($tasks as $task){
			if(in_array($task->id, [1,9])){
				$sql = "delete from new_cleaning_schedules_tasks where id = ?";
				DB::delete($sql, array($task->id));
				continue;
			}
			DB::table('new_cleaning_schedules_tasks')
				->where('id', $task->id)
				->update(['tz'=>($task->tz ? : 'Europe/London')]);

			$rDay = NULL;
			if(!$task->repeat){
				if($task->id == 11){
					$rDay = 'day';
				}
				if($task->id == 89){
					$rDay = 'week';
				}
				DB::table('new_cleaning_schedules_tasks')
					->where('repeat', '')
					->update(['repeat'=>$rDay,'repeat_freq'=>1]);
			}
			$rr = ($task->repeat!='none')?($rDay?:$task->repeat):NULL;

			$tz = ($task->tz ? : 'Europe/London');
			$items = DB::table('new_cleaning_schedules_items')->where('task_id',$task->id)->get();
			$start = \Carbon::parse($task->start,$tz)->setTime(0,0,0);
			$end = \Carbon::parse($task->end,$tz)->subHours(5)->setTime(23,59,59);
			$oldRepeatUntil = (($task->repeat!='none') ? (\Carbon::parse($task->repeat_to,$tz)->subHours(5)->setTime(23,59,59)) : NULL);
			$repeatUntil = (($task->repeat!='none') ? (\Carbon::now($tz)->addYears(1)->setTime(23,59,59)) : NULL);
			$newTask = [
				'unit_id'=>$task->unit_id,
				'staff_id'=>$task->staff_id,
				'form_id'=>$task->form_id,
				'title'=>$task->title,
				'description'=>$task->description,
				'all_day'=>($task->all_day ? 'on' : NULL),
				'start'=>$start->timezone('UTC'),
				'end'=>$end->timezone('UTC'),
				'is_repeatable'=>($task->repeat!='none')?'on':NULL,
				'repeat'=>$rr,
				'repeat_every'=>($rr?($task->repeat_freq?:1):NULL),
				'repeat_until'=>(($repeatUntil) ? $repeatUntil->timezone('UTC') : NULL),
				'weekends'=>$task->weekend,
				'tz'=>$tz,
				'status'=>0,
			];
			$boston = \Model\NewCleaningSchedulesTasks::create($newTask);
			if($boston->is_repeatable && $oldRepeatUntil->copy()->isPast()){
				\Services\CleaningSchedule::createTasksItems($boston);
			}

			foreach($items as $item){
				$newItem = [
					'unit_id'=>$item->unit_id,
					'task_id'=>$boston->id,
					'start'=>\Carbon::parse($item->start,$tz)->setTime(0,0,0)->timezone('UTC'),
					'end'=>\Carbon::parse($item->end,$tz)->subHours(2)->setTime(23,59,59)->timezone('UTC'),
				];
				$denver = \Model\NewCleaningSchedulesItems::firstOrCreate($newItem);
			}
		}
		\Services\CleaningSchedule::clearAndSubmitExpiredTasksItems();
		\Services\CleaningSchedule::createOutstandingTasks();
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
