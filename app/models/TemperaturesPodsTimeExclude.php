<?php namespace Model;

class TemperaturesPodsTimeExclude extends Models
{
    protected $table = 'temperatures_pods_time_exclude';
    protected $fillable = ['active','unit_id','area_id','week_days','all_day','from','to','repeat'];

    public function isExcluded()
    {
        $now = \Carbon::now();
        $todayStart = \Carbon::today()->startOfDay();
        $todayEnd = \Carbon::today()->endOfDay();

        $active = $this -> active;
        $days = $this -> week_days;

        if(!$active || is_null($days)){
            return false;
        }

        $days = array_intersect(explode(',',$days), range(0,6));

        $allDay = $this -> all_day;

        $from = explode(':',$this -> from);
        $to   = explode(':',$this -> to);

        $from[1] = isset($from[1]) ? $from[1] : '00';
        $to[1] = isset($to[1]) ? $to[1] : '00';

        $hours = range(0, 23);
        $minutes = range(0, 59);

        if($allDay &&
            (!in_array($from[0], $hours) ||
            !in_array($to[0], $hours) ||
            !in_array($from[1], $minutes) ||
            !in_array($to[1], $minutes))
        )
            return false;

        $from = \Carbon::createFromTime($from[0], $from[1], '00');
        $to   = \Carbon::createFromTime($to[0], $to[1], '00');

        if ($allDay) {
             return (in_array(\Carbon::now()->dayOfWeek, $days)) ? true : false;
        } else {
            if ($from > $to) {
                if (in_array(\Carbon::now()->subDay()->dayOfWeek, $days)) {
                    if (($now > $todayStart) && ($now < $to)) {
                        return true;
                    }
                }
                if (in_array(\Carbon::now()->dayOfWeek, $days)) {
                    if (($now > $from) && ($now < $todayEnd)) {
                        return true;
                    }
                }
            } else {
                if (in_array(\Carbon::now()->dayOfWeek, $days)) {
                    if (($now > $from) && ($now < $to)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}