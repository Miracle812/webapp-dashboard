<?php namespace Sections\AreaManagers;

class UnitsMap extends AreaManagersSection {

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('units-map', 'UnitsMap');
    }

    public function getIndex()
    {
        return \View::make($this->regView('index'));
    }

    public function getMapData()
    {
        $gmap = $this->getAllMapData();
        return \Response::json($gmap);
    }

    public function getAllMapData()
    {
        $user = \Auth::user();
        $units = \Model\Units::whereIn('id',$this->getUnitsId()) -> get();
        $hq = $user->headquarter();
        $data = [];
        $address = $hq->post_code.' '.$hq->city.'<br>'.$hq->street_number;
        $name = $this->getLocDesc($hq->name, $address, 'Headquarter');
        $data[] = [$name, $hq->gmap_lat, $hq->gmap_lng, 'f79546', 'H'];
        foreach ($units as $unit){
            $address = $unit->post_code.' '.$unit->city.'<br>'.$unit->street_number;
            $name = $this->getLocDesc($unit->name, $address, 'Unit');
            $data[] = [$name, $unit->gmap_lat, $unit->gmap_lng, '177bbb', 'U'];
        }
        return $data;
    }

    public function getLocDesc($name,$address,$type, $unit = null)
    {
        $html = '<div class="col-sm-12" style="height: 130px">';
        $html.= '<span class="font-bold text-navitas">'.$type.'</span><br>';
        $html.= '<span class="h4 text-primary">'.$name.'</span><br>';
        $html.= '<span class="text-default">'.$address.'</span><br>';
        $html.= $unit?'<small class="font-bold text-default">Unit: '.$unit.'</small>':'';
        $html.= '</div>';
        return $html;
    }
}
