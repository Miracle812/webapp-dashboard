<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PatchForCleaningschedulesData extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("SET foreign_key_checks = 0");
        DB::table('new_cleaning_schedules_items')->truncate();
        DB::table('new_cleaning_schedules_tasks')->truncate();
        DB::table('new_cleaning_schedules_submitted')->truncate();
        DB::statement("SET foreign_key_checks = 1");

		$records = \Model\CleaningSchedules::all();
        foreach($records as $record){

            $start     = ($record->all_day == 'true') ? \Carbon::createFromFormat('Y-m-d H:i:s', $record->start)->startOfDay() : \Carbon::createFromFormat('Y-m-d H:i:s', $record->start);
            $end       = ($record->all_day == 'true') ? \Carbon::createFromFormat('Y-m-d H:i:s', $record->end)->endOfDay() : \Carbon::createFromFormat('Y-m-d H:i:s', $record->end);
            $repeat_to = ($record->all_day == 'true') ? \Carbon::createFromFormat('Y-m-d H:i:s', $record->to)->endOfDay() : \Carbon::createFromFormat('Y-m-d H:i:s', $record->to);

            $data = [
                'unit_id' =>$record->unit_id,
                'staff_id'=>$record->staff_id,
                'form_id'=>$record->form_id,
                'title'=>$record->title,
                'description'=>$record->description,
                'type'=>$record->type,
                'start'=>$start,
                'end'=>$end,
                'repeat'=>$record->repeat,
                'repeat_freq'=>$record->repeat_freq,
                'repeat_to'=>$repeat_to,
                'weekend'=>$record->weekend,
                'all_day'=>(($record->all_day)=='true'?1:0),
                'status'=>$record->status
            ];

            $newTask = \Model\NewCleaningSchedulesTasks::create($data);
            \Services\CleaningSchedule::createTasksItems($data, $newTask);

            $items = \Model\NewCleaningSchedulesItems::whereUnitId($newTask->unit_id)->whereTaskId($newTask->id)->get();
            if($items && $items->count()){
                foreach ($items as $item) {

                    $oldSubmitted = $record -> scheduleLogs;
                    $itemStart = \Carbon::createFromFormat('Y-m-d H:i:s', $item->start)->format('Y-m-d');
                    $itemEnd = \Carbon::createFromFormat('Y-m-d H:i:s', $item->end)->format('Y-m-d');

                    $oldItem = $oldSubmitted->filter(function ($item) use ($itemStart, $itemEnd) {
                        $oldSubStart = \Carbon::createFromTimestamp($item->start)->format('Y-m-d');
                        $oldSubEnd   = \Carbon::createFromTimestamp($item->end)->format('Y-m-d');
                        return (($oldSubStart == $itemStart) && ($oldSubEnd == $itemEnd)) ? true : false;
                    });
                    if ($oldItem && ($oldItem = $oldItem->first())) {

                        $subData = [
                            'unit_id' => $item->unit_id,
                            'task_item_id' => $item->id,
                            'title' => $newTask->title,
                            'description' => $newTask->description,
                            'staff_name' => (($staff = $newTask->staff) ? $staff->fullname() : NULL),
                            'form_name' => (($form = $newTask->form) ? $form->name : NULL),
                            'form_answer_id' => $oldItem->form_answer_id,
                            'start' => $item->start,
                            'end' => $item->end,
                            'all_day' => $newTask->all_day,
                            'summary' => $oldItem->summary,
                            'completed' => $oldItem->completed,
                            'created_at' => $oldItem->created_at,
                            'updated_at' => $oldItem->updated_at,
                        ];
                        if($newTask->form && $oldItem->form_answer_id){
                            $newSubmitted = \Model\NewCleaningSchedulesSubmitted::create($subData);
                            if($formAnswer = $newSubmitted->formAnswer){
                                $formAnswer->update(['assigned'=>'new_cleaning_schedules_items,'.$item->id]);
                            }
                        }
                    }
                }
            }
            \Model\MenuStructures::where('target_type','=','local-manager')->where('route_path','=','/cleaning-schedule')->update(['route_path'=>'/new-cleaning-schedule']);
        }
        DB::table('cleaning_schedules')->truncate();
        DB::table('cleaning_schedules_log')->truncate();
        \Model\OutstandingTask::where('target_type','cleaning_schedules_log')->orWhere('target_type','forms_answers')->delete();
        \CronController::start();
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
