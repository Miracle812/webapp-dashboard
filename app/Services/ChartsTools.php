<?php
namespace Services;

class ChartsTools extends \BaseController
{
    public static function fillDays($fillFrom = 'last-month')
    {
        $self = new self();
        $sStartDate =  $self->getDateFrom($fillFrom);
        $sEndDate = \Carbon::now()->endOfDay();
        $aDays[$sStartDate->toDateString()] = 0;
        while($sStartDate < $sEndDate){
            $aDays[$sStartDate->toDateString()] = 0;
            $sStartDate = $sStartDate->addDay();
        }
        return $aDays;
    }

    public static function getDateFrom($from = 'last-month')
    {
        switch($from){
            case 'today': $from = \Carbon::now()->today(); break;
            case 'last-year': $from = \Carbon::now()->subYear(); break;
            case 'last-month': $from = \Carbon::now()->subMonth(); break;
            case 'last-week' : $from = \Carbon::now()->subWeek();  break;
            default : $from = \Carbon::now()->subMonth(); break;
        }
        return $from;
    }

    public static function renderDates($input)
    {
        foreach ($input as $key => $value){
            $data[] = [strtotime($key)*1000, $value];
        }
        return $data;
    }
}