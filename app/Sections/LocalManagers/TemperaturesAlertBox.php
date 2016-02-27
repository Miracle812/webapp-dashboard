<?php namespace Sections\LocalManagers;

class TemperaturesAlertBox extends LocalManagersSection {

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('temperatures-alert-box', 'Temperatures Alert Box');
    }
    public function getIndex()
    {
        $folders = \Model\TemperaturesAlertBox::where('unit_id','=',$this->auth_user->unit()->id)->whereNull('parent_id')->get();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('folders','breadcrumbs'));
    }

    public function getAreas($id)
    {
        $parent = \Model\TemperaturesAlertBox::find($id);

        if(!$parent || !$parent -> checkAccess())
            return $this->redirectIfNotExist();

        $areas = \Model\TemperaturesAlertBox::where('unit_id','=',$this->auth_user->unit()->id)->where('parent_id','=',$id)->get();

        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('list-areas'), compact('parent','areas','breadcrumbs'));
    }

    public function getCreateFolder()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create-folder'), compact('breadcrumbs'));
    }

    public function postCreateFolder(){

        $rules = [
            'name' => 'required|min:5'
        ];
        $validator = \Validator::make(\Input::all(), $rules);

        if(!$validator -> fails()) {
            $folder = new \Model\TemperaturesAlertBox();
            $folder -> unit_id = $this->auth_user->unitId();
            $folder -> name = \Input::get('name');
            $folder -> group = 'folder';
            $save = $folder -> save();
            if($save)
                return \Redirect::to('/temperatures-alert-box')->with('success', \Lang::get('/common/messages.create_success'));
            return \Redirect::to('/temperatures-alert-box')->with('fail', \Lang::get('/common/messages.create_fail'));
        }
        else{
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }
    public function getCreateArea($id)
    {
        $folder = \Model\TemperaturesAlertBox::find($id);
        if(!$folder || !$folder -> checkAccess())
            return $this->redirectIfNotExist();
        $groups = ['probes','pods'];
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create-area'), compact('groups','folder','breadcrumbs'));
    }

    public function postCreateArea($id)
    {
        $parent = \Model\TemperaturesAlertBox::find($id);
        if(!$parent || !$parent -> checkAccess())
            return $this->redirectIfNotExist();

        $rules = [
            'group'   => 'required',
            'area_id'   => 'numeric|required'
        ];

        if(\Input::get('name')){
            $rules['name'] = 'required|min:5';
        }
        $validator = \Validator::make(\Input::all(), $rules);
        if(!$validator -> fails()) {
            if(\Input::get('group') == 'pods')
                $area = \Model\TemperaturesPodsAreas::find(\Input::get('area_id'));
            else
                $area = \Model\TemperaturesProbesAreas::find(\Input::get('area_id'));
//area exist?
            if(!$area){
                return \Redirect::to('/temperatures-alert-box/folder/'.$id)->withErrors([\Lang::get('/common/messages.not_exist')]);
            }
//child in group exist?
            $exits =  $parent->childs()->where('area_id','=',$area->id)->first();
            if($exits){
                return \Redirect::to('/temperatures-alert-box/folder/'.$id)->withErrors([\Lang::get('/common/messages.already_exist')]);
            }

            $newArea = new \Model\TemperaturesAlertBox();
            $newArea -> parent_id = $id;
            $newArea -> unit_id = $this->auth_user->unit()->id;
            $newArea -> area_id = \Input::get('area_id');
            $newArea -> group = \Input::get('group');
            $newArea -> name = trim(\Input::get('name'))?:$area->name;
            $save = $newArea -> save();
            if($save)
                return \Redirect::to('/temperatures-alert-box')->with('success', \Lang::get('/common/messages.create_success'));
            return \Redirect::to('/temperatures-alert-box')->with('fail', \Lang::get('/common/messages.create_fail'));
        }
        else{
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function getEdit($id)
    {
        $item = \Model\TemperaturesAlertBox::find($id);
        if(!$item || !$item -> checkAccess())
            return $this->redirectIfNotExist();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('edit'), compact('item','breadcrumbs') );
    }

    public function postEdit($id)
    {
        $item = \Model\TemperaturesAlertBox::find($id);
        if(!$item || !$item -> checkAccess())
            return $this->redirectIfNotExist();
        $rules = [
            'name'              => 'required|min:5'
        ];
        $validator = \Validator::make(\Input::all(), $rules);
        if(!$validator -> fails()) {
            $item -> name = \Input::get('name');
            $update = $item -> update();
            if($update)
                return \Redirect::to('/temperatures-alert-box')->with('success', \Lang::get('/common/messages.update_success'));
            return \Redirect::to('/temperatures-alert-box')->with('fail', \Lang::get('/common/messages.update_fail'));
        }
        else{
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function getDeleteArea($id){
        $area = \Model\TemperaturesAlertBox::find($id);
        if(!$area || !$area -> checkAccess())
            return $this->redirectIfNotExist();

        $removed = false;
        if($area)
            $removed = $area->delete();
        $type = $removed?'success':'fail';
        return \Redirect::to('/temperatures-alert-box/folder/'.$area->parent->id)->with($type, \Lang::get('/common/messages.delete_'.$removed));
    }

    public function getDeleteFolder($id){
        $area = \Model\TemperaturesAlertBox::find($id);
        if(!$area || !$area -> checkAccess())
            return $this->redirectIfNotExist();
        $removed = false;
        if($area)
            $childs = $area->childs;
            if($childs){
                foreach($childs as $child)
                    $child->delete();
            }
            $removed = $area->delete();
        $type = $removed?'success':'fail';
        return \Redirect::to('/temperatures-alert-box')->with($type, \Lang::get('/common/messages.delete_'.$removed));
    }

//ajax
    public function getLoadAreas($group)
    {
        $html = ''; $data = [];
        if(!$group) return $html;
        $unitId = $this->auth_user->unitId();

        if($group == 'pods')
            $areas = \Model\TemperaturesPodsAreas::whereUnitId($unitId)->whereType('area')->orderBy('parent_id')->get();
        else
            $areas = \Model\TemperaturesProbesAreas::whereUnitId($unitId)->get();

        if($areas){
            foreach($areas as $area){
                $data[$area->id] = $area->name . ($area->group == 'pods'? ', [ Group: '.implode($area->getParentsNames(), ' / ') . ' ]' : '');
            }
        }
        if(count($data))
            $html =  \Form::select('area_id', $data, null, ['class'=>'form-control']);
        else
            $html = '<span class="text-danger m-r">'.\Lang::get('/common/messages.empty_data').'</span><a class="font-bold text-primary" href="/areas/'.$group.'">'.\Lang::get('/common/general.add').'</a>';
        return $html;
    }
}