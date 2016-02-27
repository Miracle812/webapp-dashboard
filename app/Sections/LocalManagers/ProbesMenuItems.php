<?php namespace Sections\LocalManagers;

class ProbesMenuItems extends LocalManagersSection {

    private $items;

    public function __construct(\Model\TemperaturesProbesMenuItems $items){
        parent::__construct();
        $this -> items = $items::where('unit_id', '=', $this->auth_user->unitId())->first();
        $this -> breadcrumbs -> addCrumb('probes/menu-items', 'Probe Menu Items');
    }

    public function getIndex()
    {
        $items = $this -> items;
        $tree = $items ? unserialize($items -> structure) : [];

        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make( $this -> regView('index'), compact('breadcrumbs','tree') );
    }

    public function getDelete($id)
    {
        $items = $this -> items;
        $tree = $items ? unserialize($items -> structure) : [];
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make( $this -> regView('index'), compact('breadcrumbs','tree') );
    }

    public function postUpdate()
    {
        $foodTree = serialize(\Input::get());
        $items = $this -> items;
        if($items){
            $items -> structure = $foodTree;
            $items -> update();
        }
        else {
            $items = new \Model\TemperaturesProbesMenuItems();
            $items -> user_id   = $this->auth_user->id;
            $items -> unit_id   = $this->auth_user->unit()->id;
            $items -> structure = $foodTree;
            $items -> save();
        }
        return \Response::json(['type'=>'success', 'msg'=>'Update successful']);
    }

    public function getItem($id)
    {
        $items = $this -> items;
        $item = \Services\TemperaturesProbesMenuItems::getItemByKeyValue($items->getStructureArray(),'id',$id);
        var_dump($item);
    }
}