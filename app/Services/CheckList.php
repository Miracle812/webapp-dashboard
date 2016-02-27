<?php
namespace Services;

class CheckList extends CalendarEvents
{
    function isSubmitted($task, $start)
    {
        return ($start->isPast()) ? (\Model\CheckListSubmitted::
        where('unit_id','=',$task->unit_id)->
        where('task_id',$task->id)->
        where('start',$start->timestamp)->
        where('tz',$task->tz)->
        first()) : NULL;
    }

    public static function submittedTasks($unitId,$startDate,$endDate)
    {
        $self = new self();
        $dates = [];
        $submitteds = \Model\CheckListSubmitted::
            where('unit_id','=',$unitId)->
            where(function($query)use($startDate,$endDate){
                $query ->
                where(function($query)use($startDate,$endDate){
                    $query-> where('start','>=', $startDate)-> where('start','<=',$endDate);
                })->
                orWhere(function($query){
                    $query-> whereNotNull('expired_dates');
                });
            })->
            where(function($query){
                $query-> whereNull('task_item_id')->orWhere('task_item_id',0);
            })->get();
        foreach($submitteds as $submitted){
            $startItem = \Carbon::parse($submitted->start)->timezone($submitted->tz);
            $endItem   = \Carbon::parse($submitted->end)->timezone($submitted->tz);
            if(is_null($submitted->expired_dates)) {
                $taskStatus = ($submitted->completed ? 2 : 3);
                $dates['cl-s'.$submitted->id][$startItem->timestamp] = ($self->addDate($submitted, $startItem, $endItem)+['task_status'=>$taskStatus,'item_id'=>$submitted->id]);
            }else{
                $exploded = explode(',',$submitted->expired_dates);
                if(count($exploded)){
                    foreach($exploded as $expDate){
                        $dateTs = explode(':',$expDate);
                        $startItem = \Carbon::createFromTimestamp($dateTs[0])->timezone($submitted->tz);
                        $endItem = \Carbon::createFromTimestamp($dateTs[1])->timezone($submitted->tz);
                        if(($startItem>=$startDate) && ($startItem<=$endDate)) {
                            $dates['cl-s'.$submitted->id][$startItem->timestamp] = ($self->addDate($submitted, $startItem, $endItem)+['task_status'=>4,'item_id'=>$submitted->id]);
                        }
                    }
                }
            }
        }
        return $dates;
    }

    public static function getCalendarData()
    {
        $self = new self();
        $out=[];$dates=[];
        $strQuery = \Carbon::parse(\Input::get('start'));
        $endQuery = \Carbon::parse(\Input::get('end'));
        $unitId   = \Auth::user()->unitId();
        $tasks    = \Model\CheckListTasks::where('unit_id','=',$unitId)-> get();
        foreach($tasks as $task)
        {
            $startRef = \Carbon::parse($task->start, 'UTC')->timezone($task->tz);
            $endRef   = \Carbon::parse($task->end,   'UTC')->timezone($task->tz);
            $diffSecs = $startRef->diffInSeconds($endRef);
            if (in_array($task->repeat, ['day', 'week', 'month', 'year']))
            {
                $interval = $self->getInterval($task);
                $periodStart = new \DatePeriod($startRef, $interval, $endQuery);
                foreach ($periodStart as $dpStart)
                {
                    $dpStart = \Carbon::instance($dpStart, $task->tz);
                    $date = $self->testWeekendDate($task,$dpStart);
                    $endDiff = $date->copy()->addSeconds($diffSecs);
                    $datex = $self->testWeekendDate($task,$endDiff);
                    if(!$date->isPast() && !$date->isToday() && (($date >= $strQuery) && ($date <= $endQuery)) )
                    {
                        if ($task->repeat_until) {
                            $runtil = \Carbon::parse($task->repeat_until, 'UTC')->timezone($task->tz);
                            if ($date > $runtil) {
                                break;
                            }
                        }
                        $dates['cl-f'.$task->id][$date->timestamp] = ($self->addDate($task, $date, $datex)+['task_status'=>0,'item_id'=>$task->id]);
                    }
                }
            }
            else{
                if(!$startRef->isPast() && !$startRef->isToday() && (($startRef >= $strQuery) && ($startRef <= $endQuery)) )
                {
                    $dates['cl-f'.$task->id][$startRef->timestamp] = ($self->addDate($task,$startRef,$endRef)+['task_status'=>0,'item_id'=>$task->id]);
                }
            }
            $dates = array_merge($dates,$self::ongoingTasks($task));
        }
        $dates = array_merge($dates,$self::submittedTasks($unitId,$strQuery,$endQuery));
        foreach($dates as $id => $timestamps)
        {
            foreach($timestamps as $date)
            {
                $out[] = $date;
            }
        }
        return $out;
    }

    public static function clearAndSubmitExpiredTasksItems()
    {
        \Model\CheckListItems::where('start','>',\Carbon::now())->delete();
        $items = \Model\CheckListItems::where('end','<',\Carbon::now())->get();
        foreach($items as $item){
            if ($item->getLastSubmitted()) {
                $item->delete();
                continue;
            }
            $data = $dataSub = [];
            $task = $item->task;
            if(!isset($concat[$task->id])) {
                $concat[$task->id] = [];
            }
            $data['unit_id'] = $task->unit_id;
            if ($task->staff) {
                $data['staff_name'] = $task->staff->fullname();
            }
            if ($task->form) {
                $data['form_name'] = $task->form->name;
            }
            $dataSub = array_merge($data,[
                    'title' => $task->title,
                    'task_id' => $task->id,
                    'description' => $task->description,
                    'completed' => 0,
                    'summary' => 'Task has expired and has not been completed.',
                    'tz' => $task->tz,
                    'all_day' => ($task->all_day == 'on') ? 1 : 0]
            );
            $submitted = \Model\CheckListSubmitted::firstOrCreate($dataSub);
            $startTs = \Carbon::parse($item->start)->timestamp;
            $endTs = \Carbon::parse($item->end)->timestamp;
            $concat[$task->id][] = ($startTs.':'.$endTs);
            $expiredDates = ($submitted->expired_dates?($submitted->expired_dates.','):'').($startTs.':'.$endTs);
            $submitted -> update(['expired_dates'=>$expiredDates]);
            $item->delete();
        }
    }

    public static function submitForm($inputs,$answer)
    {
        $user = \Auth::user();
        $unitId = $user->unitId();
        $taskItemId = isset($inputs['check_list_items']['id']) ? $inputs['check_list_items']['id'] : NULL;
        $answer =  is_int($answer) ? \Model\FormsAnswers::find($answer) : $answer;
        if(!$taskItemId){
            $task = new \Model\CheckListTasks();
            $task -> unit_id = $unitId;
            $task -> form_id = isset($inputs['form_base_id']) ? $inputs['form_base_id'] : NULL;
            $task -> start   = \Carbon::now($user->timezone)->startOfDay()->timezone('UTC');
            $task -> end     = \Carbon::now($user->timezone)->endOfDay()->timezone('UTC');
            $task -> all_day = 'on';
            $task -> tz = $user->timezone;
            $task -> title   =  $answer -> formLog -> name;
            $task -> save();
            $item =  new \Model\CheckListItems();
            $item -> unit_id = $unitId;
            $item -> task_id = $task -> id;
            $item -> start   = $task -> start;
            $item -> end     = $task -> end;
            $item -> save();
            $taskItemId = $item -> id;
        }
        if ($taskItemId && $answer instanceof \Model\FormsAnswers){
            $answer -> update(['assigned'=>'check_list_items,'.$taskItemId]);
            $unserialize = ($answer && isset($answer->options)) ? unserialize($answer->options) : [];
            if (isset($unserialize['compliant']) && $taskItemId) {
                if ( ($item = \Model\CheckListItems::find($taskItemId)) && ($task = $item->task) ) {
                    $submitted = new \Model\CheckListSubmitted();
                    $submitted -> unit_id = $unitId;
                    $submitted -> task_item_id = $taskItemId;
                    $submitted -> task_id = $task->id;
                    $submitted -> title = $task -> title;
                    $submitted -> description = $task -> description;
                    $submitted -> completed = ($unserialize['compliant'] == 'yes') ? 1 : 0;
                    $submitted -> form_answer_id = $answer -> id;
                    $submitted -> form_name = $answer -> formLog -> name;
                    $submitted -> tz = $task->tz;
                    $submitted -> start = $item -> start;
                    $submitted -> end = $item -> end;
                    $submitted -> all_day = ($task -> all_day=='on')?1:0;//to do change to "string = on"
                    if ($staff = $task -> staff)
                        $submitted -> staff_name = $staff -> fullname();
                    $save = $submitted -> save();
                    if($save) //clear OT if completed
                    {
                        if($submitted->completed)
                        {
                            $item->delete();
                        }
                    }
                }
            }
        }
    }
}