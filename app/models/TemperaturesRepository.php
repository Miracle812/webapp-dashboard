<?php
use \Illuminate\Support\Collection;

class TemperaturesRepository
{
    function __construct($record=null)
    {
        $this -> record = $record;
    }
    public function getInvalidTemperatures()
    {
        $coll = new Collection;
        $tempsNonCompGoodsIn = $this->getInvalidEntity('TemperaturesForGoodsIn');
        $tempsNonCompPods = $this->getInvalidEntity('TemperaturesForPods');
        $tempsNonCompProbes = $this->getInvalidEntity('TemperaturesForProbes');
        return $coll->merge($tempsNonCompGoodsIn)->merge($tempsNonCompPods)->merge($tempsNonCompProbes)->sortByDesc('created_at')->take(100);
    }

    public function getInvalidEntity($model)
    {
        $model = '\Model\\' . $model;
        $data = $model::
            where(function ($query) {
                $query->whereNotNull('invalid_id');
                $query->where('invalid_id', '>', 0);
            });
            if ($record = $this->record) {
                $data = $data->whereIn('unit_id', $record->unit_id);
            }
        return $data -> get();
    }

    public function getInvalidByArea($temperature)
    {
        $table = $temperature->getTable();
        $select = '';
        switch ($table) {
            case 'temperatures_for_goods_in' : $goodsIn = true; break;
            case 'temperatures_for_pods' : $type = 'pods'; $goodsIn = false; break;
            case 'temperatures_for_probes' : $type = 'probes'; $goodsIn = false; break;
        }
        $data = \DB::table($table.' as temps_table')
            -> where  ('temps_table.unit_id', $temperature->unit_id);
        if(!$goodsIn) {
            $data = $data->where('temps_table.area_id', $temperature->area_id)
                -> join ('temperatures_'.$type.'_areas as area', 'temps_table.area_id', '=', 'area.id');
            $select = ',area.name as area_name';
        }else{
            //$data = $data->where('temps_table.device_id', $temperature->device_id);
        }
        $data
            -> join   ('outstanding_task as task', 'task.target_id', '=', \DB::raw( 'temps_table.id' ) )
            -> where  ('task.target_type',$table)
            -> where  ('task.status',0)
            -> select (\DB::raw('temps_table.*,task.status'.$select));
        return $data -> get();
    }

    public function getTempsAreaName($record)
    {
        switch ($record->getTable()){
            case 'temperatures_for_pods' : $area = 'Pods temps / '. $record -> area -> name; break;
            case 'temperatures_for_probes' : $area = 'Probes temps / '. $record -> area -> name; break;
            case 'temperatures_for_goods_in' : $area = 'Goods In.'; break;
            default: $area = 'N/A'; break;
        }
        return $area;
    }
}