<?php
namespace Repositories;
class SupportTickets
{
    public $object;
    public function __construct($object = null)
    {
        $this->object = $object;
    }

    public function isInvalid()
    {
        return $this -> object;
    }

    public function getStatusHtml()
    {
        $status = $this->getStatusName();
        switch($status){
            case 'open' : $class = 'btn-danger';  break;
            case 'answer' : $class = 'btn-warning'; break;
            case 'close' :
            default : $class = 'btn-success'; break;
        }
        return '<div class="btn btn-xs '.$class.'">'.\Lang::get('/common/general.'.$status).'</div>';
    }

    public function getStatusName(){
        $object = $this -> object;
        return $object -> statuses [$object->status];
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