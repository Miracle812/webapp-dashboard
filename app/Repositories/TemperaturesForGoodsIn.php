<?php
namespace Repositories;
class TemperaturesForGoodsIn
{
    public $object;
    public function __construct($object = null)
    {
        $this->object = $object;
    }

    public function isInvalid()
    {
        return $this -> object;;
    }

    public function getScoresData()
    {
        $object = $this -> object;
        if($message = $this-> getInvalid()) {
            return [
                'type'          => \Config::get('scores.sections.'.($table = $object -> getTable()).'.type'),
                'value'         => \Config::get('scores.sections.'.$table.'.value'),
                'message'       => $message,
            ];
        };
        return [];
    }

    public function getInvalid()
    {
        return $this -> object -> getAllNonCompliant();
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