<?php namespace Sections\Admins;

class UnitsMap extends AdminsSection {

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
        $units = \Model\Units::all();
        $headquarters = \Model\Headquarters::all();
        $data = [];
        foreach ($headquarters as $hqeadquarter){
            $address = $hqeadquarter->post_code.' '.$hqeadquarter->city.'<br>'.$hqeadquarter->street_number;
            $name = $this->getLocDesc($hqeadquarter->name, $address, 'Unit');
            $data[] = [$name, $hqeadquarter->gmap_lat, $hqeadquarter->gmap_lng, '177bbb', 'U'];
        }
        foreach ($units as $unit){
            $address = $unit->post_code.' '.$unit->city.'<br>'.$unit->street_number;
            $name = $this->getLocDesc($unit->name, $address, 'Headquarter');
            $data[] = [$name, $unit->gmap_lat, $unit->gmap_lng, 'f79546', 'H'];
        }
        return $data;
    }

    public function getLocDesc($name,$address,$type, $unit = null)
    {
        $html = '<div class="col-sm-12" style="height: 130px">';
        $html.= '<span class="font-bold text-navitas">'.$type.'</span><br>';
        $html.= '<span class="text-primary">'.$name.'</span><br>';
        $html.= '<span class="text-default">'.$address.'</span><br>';
        $html.= $unit?'<small class="font-bold text-default">Unit: '.$unit.'</small>':'';
        $html.= '</div>';
        return $html;
    }
}
