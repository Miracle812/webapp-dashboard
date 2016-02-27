<?php namespace Model;

class TemperaturesForProbes extends TemperaturesModel {

    protected $fillable = ['rules_id','invalid_id'];

    public function probe()
    {
        return $this->belongsTo('\Model\ProbesDevices', 'device_id');
    }

    public function pair()
    {
        return $this->belongsTo('\Model\TemperaturesForProbes', 'pair_id');
    }

    public function area()
    {
        return $this->belongsTo('\Model\TemperaturesProbesAreas', 'area_id');
    }

    public function repository()
    {
        return \App::make('\Repositories\TemperaturesForProbes', [$this]);
    }
/*
    public function getIssueNotification($destination)
    {
        if($this->invalid){
            $bid = 'prob-temp';
            $bid = 'moreless-'.$destination.'-'.$bid.'-'.$this->id;
            $content =
                '<a href="/temperatures/'.$this->area->group->identifier.'/'.$this->area->id.'">'.
                    '<span class="text-danger font-bold">Temperature valid range ('.$this->invalid->exceed.'imal value '.$this->invalid->temperature.'&#x2103) was exceeded.</span>'.
                '</a>'.
                '<div id="'.$bid.'" class="text-muted text-xs clear" style="display: none;">'.
                    '<b>Area:</b> '.$this->area->group->name .' / '.$this->area->name.'<br>'.
                    '<b>Last temp:</b> '.$this->temperature.'&#x2103<br>'.
                    '<b>Staff:</b> '.$this->staff_name.'<br>'.
                    '<b>Item:</b> '.$this->item_name.'<br>'.
                    '<b>Device:</b> '.$this->device_name.'<br>'.
                '</div>';
            return $this->getNotificationTemplate($destination,$content,$bid);
        }
    }
*/
}