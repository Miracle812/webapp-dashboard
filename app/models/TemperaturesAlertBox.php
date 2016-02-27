<?php namespace Model;

use Carbon\Carbon;

class TemperaturesAlertBox extends Models {

    protected $fillable = ['area_id','group','name'];
    protected $table = 'temperatures_alert_box';
    protected $defPlaceholder = ['celsius'=>'&#x2103'];

    public function unit()
    {
        return $this->belongsTo('\Model\Units', 'unit_id');
    }

    public function parent()
    {
        return $this->belongsTo('\Model\TemperaturesAlertBox', 'parent_id', 'id');
    }

    public function childs()
    {
        return $this->hasMany('\Model\TemperaturesAlertBox', 'parent_id');
    }

    public function area()
    {
        return $this -> group == 'pods' ?
            $this -> belongsTo('\Model\TemperaturesPodsAreas', 'area_id') :
            $this -> belongsTo('\Model\TemperaturesProbesAreas', 'area_id');
    }

    public function getLastToday()
    {

        $childs = $this->childs()->get();
        $collection = new \Illuminate\Database\Eloquent\Collection();
        foreach($childs as $child){
            if($child->area && ($temperature = $child->area->getLastTempToday())){
                $collection->add($temperature);
            }
        }

        $lasts = $collection->sortByDesc('created_at')->first();
        return $lasts;
    }

    public function checkDataToday()
    {
        $childs = $this->childs()->get();
        $out = null;
        foreach($childs as $child){
            $area = $child->area;
            if ($area) {
                //$lastTemp = $area -> getLastTemperature();
                $checkData = $area -> checkDataToday();
                //if($lastTemp->invalid){
                //    return false;
                //}
                $out[$checkData===null?'null':'true'];
            }
        }
        return isset($out['true'])?true:null;
    }
}