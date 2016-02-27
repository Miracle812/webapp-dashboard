<?php namespace Model;

class PodsDevices extends Models {

    protected $fillable = [
        'name',
        'area_id',
        'description',
        'identifier'
    ];

    public $rules = [
        'name'        => 'required|min:3|max:30',
        'area_id'     => 'required|numeric',
        'area_group'  => 'required',
        'description' => 'required|max:150',
        'identifier'  => 'required|AlphaNum|max:6|min:6|unique:pods_devices',
    ];

    public function area()
    {
        return $this -> belongsTo('\Model\TemperaturesAreas', 'area_id');
    }

    public function temperatures()
    {
        return $this -> hasMany('\Model\TemperaturesForPods', 'pod_id');
    }

    public function getLastTemperature($limit = 1)
    {
        $temperatures = $this -> temperatures() -> orderBy('id', 'DESC') -> limit($limit);
        return $limit == 1 ?  $temperatures -> first() : $temperatures -> get();
    }

    public function getHub()
    {
        $temp = $this -> getLastTemperature();
        return $temp ? $temp -> hub : null;
    }

    public function getLastHub()
    {
        $hub = $this -> getHub();
        return $hub ? \Model\HubsDevices::whereIdentifier($hub -> identifier)->orderBy('id','DESC')->limit(1)->first() : null;
    }
}
