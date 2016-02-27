<?php namespace Model;

class TemperaturesModel extends Models
{
    protected $section_url = '/temperatures/';

    public function unit()
    {
        return $this->belongsTo('\Model\Units', 'unit_id');
    }



    public function probe()
    {
        return $this->belongsTo('\Model\TemperaturesProbesDevices', 'device_identifier', 'identifier');
    }

    public function pod()
    {
        return $this->belongsTo('\Model\TemperaturesPodsSensors', 'device_identifier', 'identifier');
    }

    public function rule()
    {
        return $this->belongsTo('\Model\TemperaturesLogRules', 'rules_id');
    }

    public function area()
    {
        return $this->belongsTo('\Model\TemperaturesLogRules', 'area_id');
    }

    public function invalid()
    {
        return $this->belongsTo('\Model\TemperaturesLogInvalid', 'invalid_id');
    }

    public function resolved()
    {
        return $this->belongsTo('\Model\TemperaturesResolved', 'resolved_id');
    }

    public function temperature()
    {
        return $this->temperature.'&#x2103';
    }

    public function date_time()
    {
        $tz = \Config::get('app.timezone');
        if(\Auth::check()){
            $tz = \Auth::user()->timezone;
        }
        if(!$this -> date_time)
            $this -> update(['date_time'=>strtotime($this->created_at)]);
        if(strlen($this->date_time) < 11)
            $timestamp = $this->date_time;
        else
            $timestamp = ($this->date_time/1000);
        return \Carbon::createFromTimeStamp($timestamp,$tz)->format('d-m-Y H:i');
    }

    public function device()
    {
        $group = $this->area?$this->area->group:false;
        if($group){
            switch ($group){
                case 'pods': return $this->pod(); break;
                case 'probes': return $this->probe(); break;
                default : return []; break;
            }
        }
        return false;
    }

    public function getPopoverButton(){
        $service = new \Services\Temperatures();
        return $service->getPopoverButton($this,['placement'=>'left']);
    }

    public function isStatusValidToday()
    {
        $todayTemps = $this->
            where('created_at','>',$this->created_at->startOfDay())->
            where('created_at','<',$this->created_at->endOfDay())->
            where('unit_id',$this->unit_id)->
            where('area_id',$this->area_id)->
            get();
        $invalidTemps = $todayTemps->filter(function($item){
            return ($item->invalid_id) ? true : false;
        });
        return ($todayTemps->count() ? ($invalidTemps->count() ? 3 : 2) : 1); //1 = no temperatures, 2 = valid temperautres , 3 invalid temperatures
    }

    public function getSectionName()
    {
        $table = $this -> getTable();
        $area  = $this -> area;
        $group = $area -> group;

        return \Lang::get('/common/sections.'.$table.'.title');
    }

    public function getUrl( $type = 'item' )
    {
        $area  = $this -> area;
        $group = $area -> group;
        switch ($type){
            case 'item' : $url = $group.'/'.$area->id; break;
            case 'section' : $url = $group; break;
            default : $url = ''; break;
        }
        return $this -> section_url . $url ;
    }

    public function getOutstandingTitle()
    {
        $table = $this -> getTable();
        $area  = $this -> area;
        $group = $area -> group;

        $title1 = \Lang::get('/common/general.'.$group) . '/' . $area->name;
        $title2 = \Lang::get('/common/sections.'.$table.'.messages.outstanding_tasks');

        return  $title1."<br>".$title2;
    }

    public function getOutstandingTaskItemTitle($details = null)
    {
        $invalid = '';
        if($this->invalid){
            $type = $this->invalid->type;
            $invalid = '<span class="font-bold text-'.$type.'"> ('.ucfirst($type).')</span>';
        }
        $details = $details ? $details : \Lang::get('/common/general.area').': '.$this->area->name.', '.\Lang::get('/common/general.temperature').': '.$this->temperature();
        return
            parent::getOutstandingTaskItemTitle() . ' ' . $invalid . '<br>' .
            $details;
    }
}