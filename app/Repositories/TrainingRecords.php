<?php
namespace Repositories;
class TrainingRecords
{
    public $object;
    public function __construct($object = null)
    {
        $this->object = $object;
    }

    public function isInvalid()
    {
        return ($this->isExpired());
    }

    public function getScoresData()
    {
        $object = $this -> object;
        if($this-> isExpired()) {
            return [
                'type'          => \Config::get('scores.sections.'.($table = $object -> getTable()).'.type'),
                'value'         => \Config::get('scores.sections.'.$table.'.value'),
                'message'       => 'Training record (refresh date) has expired.',
            ];
        };
        return [];
    }

    public function outstandingTask()
    {
        return $this->isExpired();
    }

    public function isExpired()
    {
        $object = $this -> object;
        $date = \Carbon::createFromTimestamp(strtotime($object -> date_refresh),'UTC') -> setTime(23,59,59);
        return (\Carbon::now('UTC') > $date);
    }

    public function toExpire()
    {
        $object = $this -> object;
        $now = \Carbon::now('UTC');
        $expiry = \Carbon::createFromTimestamp(strtotime($object -> date_refresh),'UTC');
        $normal = $expiry -> diff($now)->invert;
        $days   = $expiry -> diffInDays($now);
        $weeks  = $expiry -> diffInWeeks($now);
        if(!$normal){
            $days  = -$days;
            $weeks = -$weeks;
        }
        return ['days'=>$days,'weeks' => $weeks];
    }

    public function expireBullets(){
        $expire = $this->toExpire();
        $a = array_fill(0, 4, 'danger');
        if(is_array($expire)){
            if($expire['weeks'] <= 0)
                $limit = 0;
            elseif($expire['weeks'] > 4)
                $limit = 4;
            else
                $limit = $expire['weeks'];

            $b = ($limit > 0) ? array_fill(0, $limit, 'success'):[];
            return array_reverse($b)+$a;
        }
        return $a;
    }
}