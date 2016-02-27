<?php
namespace Services;

class Workflow extends \BaseController
{

    public static function cronDailyJob()
    {
        $self = new self();
        $self -> createTasksItems();
    }

    public static function cronHourlyJob()
    {
        $self = new self();
        $self -> clearExpiredTask();
    }

    public static function clearExpiredTask()
    {
        $officers = \User::whereHas('roles',function($query){
            $query->where('role_id', 7);
        })->with('units')->get();

        $sites = [];
        foreach(\Model\WorkflowItems::all() as $item)
        {
            $task = $item -> task;
            $author = $task-> author;
            if(\Carbon::parse($item -> date) -> endOfDay()->isPast()) {
                $sites[$item->task_id]['sites'][]=$item->site_id;
                $officerArr = $officers->filter(function($officer)use($item){
                    return in_array($item->site_id, $officer -> units -> lists('id'));
                })->lists('id');
                $sites[$item->task_id]['officers'] = implode(',',$officerArr);

                $task = \Model\WorkflowCompleted::firstOrCreate(
                    [
                        'task_id'    => $item -> task_id,
                        'title'      => $task -> title,
                        'description'=> $task -> description,
                        'date'       => $item -> date,
                        'author_id'  => $task -> author_id,
                        'author'     => $author -> fullname(),
                        'officer_id' => NULL,
                        'officer'    => 'Crontab',
                        'tz'         => $task -> tz,
                        'completed'  => 0
                    ]);
                $task -> update( [ 'summary'=>serialize($sites[$item->task_id])]);
                $item -> delete();
            }
        }
    }

    public static function createTasksItems($tasksCollection = null)
    {
        $tasks = $tasksCollection ? : \Model\WorkflowTasks::all();
        foreach($tasks as $task)
        {
            $self = new self();
            $self -> createTaskItems($task);
        }
    }

    public static function createTaskItems($task)
    {
        $dates = [];
        foreach(\Model\Units::all() as $site)
        {
            if($task->assigned_sites !== 'default'){
                $sitesArr = explode(',',$task->assigned_sites);
                if(!in_array($site->id, $sitesArr)) {
                    continue;
                }
            }
            if(in_array($task->repeat,['daily','weekly','monthly', 'yearly'])) {
                $begin = \Carbon::parse($site->created_at, 'UTC')->timezone($task->tz)->startOfDay();
                $frq = ($task->frequency > 1) ? $task->frequency : '';
                switch ($task->repeat) {
                    case 'daily' :
                        $queryStr = 'next ' . $frq . ' days';
                        break;
                    case 'weekly' :
                        $queryStr = 'next ' . $frq . ' weeks';
                        break;
                    case 'monthly' :
                        $queryStr = 'next ' . $frq . ' months';
                        break;
                    case 'yearly' :
                        $queryStr = 'next ' . $frq . ' years';
                        break;
                }
                /*
                'next X (day|week|month|year)',
                'end of next X (week|month|year)',
                'start of next X (week|month|year)',
                'next X (monday|tuesday|wednesday|thursday|friday|saturday|sunday),

                ['end of next week','end of next 1 month','end of next 1 year'];
                ['start of next 1 week','start of next 1 month','start of next 1 year'];
                ['next 1 friday'];
                ['next month'];
                */
                $interval = \DateInterval::createFromDateString($queryStr);
                $period = new \DatePeriod($begin, $interval, \Carbon::now($task->tz)->endOfDay(), \DatePeriod::EXCLUDE_START_DATE);
                foreach ($period as $dt) {
                    $dt = \Carbon::instance($dt,$task->tz);
                    if (!$task->weekend && $dt->isWeekend()) {
                        \Carbon::setTestNow($dt);
                        $date = new \Carbon('last friday');
                        \Carbon::setTestNow();
                    } else {
                        $date = $dt;
                    }

                    if ($date->isToday()) {
                        $data = [
                            'site_id' => $site->id,
                            'task_id' => $task->id,
                            'date' => $date->format('Y-m-d')
                        ];
                        $dates[$site->id][$task->id] = $date->format('Y-m-d');
                        \Model\WorkflowItems::firstOrCreate($data);
                    }
                }
            }
        }
        return $dates;
    }
}