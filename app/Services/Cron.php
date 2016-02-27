<?php
namespace Services;

class Cron extends \BaseController
{
    public static function daily()
    {
        //'temperatures_for_probes'
        $tables = ['training_records','cleaning_schedules_items','check_list_items','food_incidents','staff','users','temperatures_for_goods_in','temperatures_for_pods'];
        foreach ($tables as $table) {
            \Services\Scores::scoresUpdateByTable($table);
        }
        if(\Carbon::now()->day==1) {
            \Services\Scores::resetScores();
        }
        \Services\Workflow::cronDailyJob();
        \Services\CalendarEvents::addCalendarsOngoingDates();
        \Services\CalendarEvents::removeCalendarsExpiredTasks();
    }

    public static function hourly()
    {
        \Services\Workflow::cronHourlyJob();
        \Services\CalendarEvents::clearAndSubmitExpiredCalendarsTasksItems();
        $inst = new self();
        $inst -> expireDaysUpdate();
        //\Services\Xero::cronTask();
    }

    public static function minutes($minutes)
    {
        if (($minutes > 0) && ($minutes <= 5)){
            \Services\AutoMessages::sendAutoMessages();
        }

        elseif (($minutes > 5) && ($minutes <= 10)){

        }

        elseif (($minutes > 10) && ($minutes <= 30)){

        }
        else {

        }
    }

    public function expireDaysUpdate()
    {
        $trainings = \Model\TrainingRecords::all();
        foreach ($trainings as $training) {
            if(!$training->staff){
                $training->delete();
                continue;
            }
            $toExpire = $training -> repository() -> toExpire();
            $training -> to_expire = $toExpire['days'];
            $training -> update();
        }
    }
}