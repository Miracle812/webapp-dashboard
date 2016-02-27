<?php namespace Services;

class CalendarEvents
{
    function getInterval($task){
        $frq = ($task->repeat_every > 1) ? $task->repeat_every : '';
        switch ($task->repeat) {
            case 'day' :
                $queryStr = 'next ' . $frq . ' days';
                break;
            case 'week' :
                $queryStr = 'next ' . $frq . ' weeks';
                break;
            case 'month' :
                $queryStr = 'next ' . $frq . ' months';
                break;
            case 'year' :
                $queryStr = 'next ' . $frq . ' years';
                break;
        }
        return \DateInterval::createFromDateString($queryStr);
    }

    function testWeekendDate($task,$date)
    {
        if (!$task->weekend && $date->isWeekend()) {
            \Carbon::setTestNow($date);
            $datex = new \Carbon('last friday');
            \Carbon::setTestNow();
            return $datex;
        } else {
            return $date;
        }
    }

    public static function addCalendarsOngoingDates()
    {
        $self = new self();
        foreach(['cleaning_schedules_tasks','check_list_tasks','compliance_diary_tasks'] as $table){
            switch($table){
                case 'cleaning_schedules_tasks' : $model = '\Model\CleaningSchedulesTasks'; break;
                case 'check_list_tasks' :         $model = '\Model\CheckListTasks'; break;
                case 'compliance_diary_tasks' :   $model = '\Model\ComplianceDiaryTasks'; break;
                default : $model = null; break;
            }
            if($model){
                $tasks = $model::all();
                foreach ($tasks as $task ){
                    $self->createTasksItems($task);
                }
            }
        }
    }

    public static function clearAndSubmitExpiredCalendarsTasksItems()
    {
        foreach(['cleaning_schedules_tasks','check_list_tasks','compliance_diary_tasks'] as $table){
            switch($table){
                case 'cleaning_schedules_tasks' :
                    $mTask = '\Model\CleaningSchedulesTasks';
                    $mItems = '\Model\CleaningSchedulesItems';
                    $mSubmit = '\Model\CleaningSchedulesSubmitted';
                    break;
                case 'check_list_tasks' :
                    $mTask = '\Model\CheckListTasks';
                    $mItems = '\Model\CheckListItems';
                    $mSubmit = '\Model\CheckListSubmitted';
                    break;
                case 'compliance_diary_tasks' :
                    $mTask = '\Model\ComplianceDiaryTasks';
                    $mItems = '\Model\ComplianceDiaryItems';
                    $mSubmit = '\Model\ComplianceDiarySubmitted';
                    break;
                default : $mTask = $mItems = $mSubmit = null; break;
            }
            if($mTask && $mItems && $mSubmit)
            {
                $mItems::where('start','>',\Carbon::now())->delete();
                $items = $mItems::where('end','<',\Carbon::now())->get();
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
                    $submitted = $mSubmit::firstOrCreate($dataSub);
                    $startTs = \Carbon::parse($item->start)->timestamp;
                    $endTs = \Carbon::parse($item->end)->timestamp;
                    $concat[$task->id][] = ($startTs.':'.$endTs);
                    $expiredDates = ($submitted->expired_dates?($submitted->expired_dates.','):'').($startTs.':'.$endTs);
                    $submitted -> update(['expired_dates'=>$expiredDates]);
                    $item->delete();
                }
            }
        }
    }

    public static function removeCalendarsExpiredTasks()
    {
        foreach(['cleaning_schedules_tasks','check_list_tasks','compliance_diary_tasks'] as $table){
            switch($table){
                case 'cleaning_schedules_tasks' : $model = '\Model\CleaningSchedulesTasks'; break;
                case 'check_list_tasks' :         $model = '\Model\CheckListTasks'; break;
                case 'compliance_diary_tasks' :   $model = '\Model\ComplianceDiaryTasks'; break;
                default : $model = null; break;
            }
            if($model){
                $tasks = $model::all();
                foreach ($tasks as $task){
                    if(!$task->is_repeatable){
                        $end = \Carbon::parse($task->end,'UTC');
                        if($end->isPast()){
                            $task->delete();
                        }
                    }else{
                        $repeatUntil = \Carbon::parse($task->repeat_until,'UTC');
                        if($repeatUntil->isPast()){
                            $task->delete();
                        }
                    }
                }
            }
        }
    }

    public static function createTasksItems($task)
    {
        $self = new self();

        $startRef = \Carbon::parse($task->start, 'UTC')->timezone($task->tz);
        $endRef   = \Carbon::parse($task->end, 'UTC')->timezone($task->tz);
        $diffSecs = $startRef->diffInSeconds($endRef);

        if (in_array($task->repeat, ['day', 'week', 'month', 'year'])){
            $interval = $self->getInterval($task);
            $periodStr = new \DatePeriod($startRef, $interval, \Carbon::now($task->tz)->endOfDay());
            foreach ($periodStr as $dt)
            {
                $dt    = \Carbon::instance($dt, $task->tz);
                $start = $self->testWeekendDate($task,$dt);
                $end   = $self->testWeekendDate($task,$dt->copy()->addSeconds($diffSecs));
                $self->saveToday($task,$start,$end);
            }
        }
        else{
            $self->saveToday($task,$startRef,$endRef);
        }
        return $task->items;
    }

    function addDate($task,$start,$end)
    {
        switch($task->getTable()){
            case 'cleaning_schedules_tasks' :
            case 'cleaning_schedules_submitted' :
                $section = 'cleaning-schedule';
            break;
            case 'check_list_tasks' :
            case 'check_list_submitted' :
                $section = 'check-list';
            break;
            case 'compliance_diary_tasks' :
            case 'compliance_diary_submitted' :
                $section = 'compliance-diary';
            break;
        }
        return [
            'task_id'       => $task->id,
            'start'         => $start->toAtomString(),
            'end'           => $end->toAtomString(),
            'title'         => $task->title,
            'color'         => $task->task_color,
            'section'       => $section,
            'editable'      => false
        ];
    }

    public static function ongoingTasks($task)
    {
        $dates = [];
        $self = new self();
        $ongoings = $task->items;

        switch($task->getTable()){
            case 'cleaning_schedules_tasks' : $sig = 'cs-'; break;
            case 'check_list_tasks' : $sig = 'cl-'; break;
            case 'compliance_diary_tasks' : $sig = 'cd-'; break;
        }

        foreach($ongoings as $ongoing){
            $startO = \Carbon::parse($ongoing->start,'UTC')->timezone($task->tz);
            $endO   = \Carbon::parse($ongoing->end,'UTC')->timezone($task->tz);
            $dates[$sig.'o'.$task->id][$startO->timestamp] = ($self->addDate($task, $startO, $endO)+['task_status'=>1,'item_id'=>$ongoing->id]);
        }
        return $dates;
    }

    function isOngoing($task,$start,$end)
    {
        switch($task->getTable()){
            case 'cleaning_schedules_tasks' : $model = '\Model\CleaningSchedulesItems'; break;
            case 'check_list_tasks' : $model = '\Model\CheckListItems'; break;
            case 'compliance_diary_tasks' : $model = '\Model\ComplianceDiaryItems'; break;
        }
        $data = [
            'unit_id' => $task->unit_id,
            'task_id' => $task->id,
            'start'   => $start->toDatetimeString(),
            'end'     => $end->toDatetimeString(),
        ];
        return $model::where($data)->first();
    }

    function saveToday($task,$start,$end)
    {
        switch($task->getTable()){
            case 'cleaning_schedules_tasks' : $model = '\Model\CleaningSchedulesItems'; break;
            case 'check_list_tasks' : $model = '\Model\CheckListItems'; break;
            case 'compliance_diary_tasks' : $model = '\Model\ComplianceDiaryItems'; break;
        }
        if (isset($model) && $start->isToday() || $end->isToday() || (($start->isToday() || $start->isPast()) && !$end->isPast()))
        {
            $data = [
                'unit_id' => $task->unit_id,
                'task_id' => $task->id,
                'start'   => $start->copy()->timezone('UTC')->toDatetimeString(),
                'end'     => $end->copy()->timezone('UTC')->toDatetimeString(),
            ];
            return $model::firstOrCreate($data);
        }
        return null;
    }
}