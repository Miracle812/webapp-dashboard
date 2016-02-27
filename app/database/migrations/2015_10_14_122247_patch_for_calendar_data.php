<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PatchForCalendarData extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$tasks = \Model\NewCleaningSchedulesTasks::all();
        foreach($tasks as $task) {
            $items = $task->items;
            if (!$items) {
                $task->delete();
                continue;
            } else {
                if ($task->all_day){
                    $start = \Carbon::parse($task->start)->setTime(0,0,0);
                    $end = clone $start;
                    $end = $end->addDays(1);
                    $repeat_to = \Carbon::parse($task->repeat_to)->addDays(1)->setTime(0,0,0);
                    $tz = 'Europe/London';
                    $update = ['start'=>$start,'end'=>$end,'tz'=>$tz,'repeat_to'=>$repeat_to];
                    $task->update($update);

                    foreach($items as $item){
                        $itemStart = \Carbon::parse($item->start)->setTime(0,0,0);
                        $itemEnd = clone $itemStart;
                        $itemEnd = $itemEnd->addDays(1);
                        $expiry = clone $itemEnd;
                        $expiry = $expiry->addHours(3);
                        $itemUpdate = ['start'=>$itemStart,'end'=>$itemEnd,'expiry'=>$expiry];
                        $item->update($itemUpdate);
                        $submitteds = $item->submitted;
                        foreach($submitteds as $submitted){
                            $submitted->update(['start'=>$itemStart,'end'=>$itemEnd]);
                        }
                    }
                }
                else{
                    $task->delete();
                    continue;
                }
            }
        }

        $tasks = \Model\CheckListTasks::all();
        foreach($tasks as $task) {
            $items = $task->items;
            if (!$items) {
                $task->delete();
                continue;
            } else {
                if ($task->all_day){
                    $start = \Carbon::parse($task->start)->setTime(0,0,0);
                    $end = clone $start;
                    $end = $end->addDays(1);
                    $repeat_to = \Carbon::parse($task->repeat_to)->addDays(1)->setTime(0,0,0);
                    $tz = 'Europe/London';
                    $update = ['start'=>$start,'end'=>$end,'tz'=>$tz,'repeat_to'=>$repeat_to];
                    $task->update($update);

                    foreach($items as $item){
                        $itemStart = \Carbon::parse($item->start)->setTime(0,0,0);
                        $itemEnd = clone $itemStart;
                        $itemEnd = $itemEnd->addDays(1);
                        $expiry = clone $itemEnd;
                        $expiry = $expiry->addHours(3);
                        $itemUpdate = ['start'=>$itemStart,'end'=>$itemEnd,'expiry'=>$expiry];
                        $item->update($itemUpdate);
                        $submitteds = $item->submitted;
                        foreach($submitteds as $submitted){
                            $submitted->update(['start'=>$itemStart,'end'=>$itemEnd]);
                        }
                    }
                }
                else{
                    $task->delete();
                    continue;
                }
            }
        }

        $ots = \Model\OutstandingTask::all();
        foreach($ots as $ot) {
            switch ($ot->target_type){
                case 'temperatures_for_pods' :
                    $expDat = '0000-00-00 00:00:00';
                    break;
                case 'check_list_actions' :
                    $ot->delete();
                    continue;
                    break;
                case 'food_incidents' :
                case 'forms_answers' :
                case 'new_cleaning_schedules_items' :
                case 'temperatures_for_goods_in' :
                    $expDat = \Carbon::parse($ot->expiry_date)->setTime(3,0,0);
                    break;
                case 'training_records' :
                    $expDat = \Carbon::parse($ot->expiry_date)->addDays(1)->setTime(0,0,0);
                    break;
            }
            if($ot->expiry_date == '0000-00-00 00:00:00'){

            }
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
