<?php namespace Sections\LocalManagers;

class ProbesAreas extends LocalManagersSection {


    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('probes/areas', 'Probe Areas');;
    }

    public function getIndex()
    {
        $areas = \Model\TemperaturesProbesAreas::whereUnitId(\Auth::user()->unitId())->get();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make( $this -> regView('index'), compact('areas','breadcrumbs') );
    }

    public function getEditArea($id)
    {
        $area = \Model\TemperaturesProbesAreas::find($id);
        if(!$area || !$area -> checkAccess())
            return $this -> redirectIfNotExist();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this -> regView('probes.edit'), compact('area','breadcrumbs') );
    }

    public function postEditArea($id)
    {
        $area = \Model\TemperaturesProbesAreas::find($id);
        if(!$area || !$area -> checkAccess())
            return $this -> redirectIfNotExist();
        $rules = $area -> rules;
        unset($rules['name'],$rules['rule_description']);
        $input = \Input::all();
        $validator = \Validator::make($input, $rules);
        if($input['warning_min'] > $input['valid_min']){
            $validator->getMessageBag()->add('warning_min', 'Danger min is greater then Warning min');
            $validator->getMessageBag()->add('valid_min', 'Warning max is less then Danger min');
        }
        if($input['warning_max'] < $input['valid_max']){
            $validator->getMessageBag()->add('warning_max', 'Danger max is less then Warning max');
            $validator->getMessageBag()->add('valid_max', 'Warning max is greater then Danger max');
        }
        if(!$validator -> errors() -> count()) {
            $area -> fill($input);
            $update = $area -> update();
            $type = $update ? 'success' : 'fail';
            $msg  = \Lang::get('/common/messages.update_'.$type);
            return \Redirect::back()->with($type, $msg);
        }
        else {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function getCreate($group)
    {
        $group = \Model\TemperaturesGroups::whereIdentifier($group)->first();
        if(!$group || $group->identifier == 'probes')
            return $this -> redirectIfNotExist();

        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction($group->identifier.'.create') );
        return \View::make($this -> regView($group->identifier.'.create'), compact('group','breadcrumbs') );
    }

    public function postCreate($group)
    {
        if($group=='probes')
            return $this -> redirectIfNotExist();
        $new  = new \Model\TemperaturesAreas();
        $rules = $new -> rules;
        $input = \Input::all();
        $validator = \Validator::make($input, $rules);

        if($input['warning_min'] > $input['valid_min']){
            $validator->getMessageBag()->add('warning_min', 'Danger min is greater then Warning min');
            $validator->getMessageBag()->add('valid_min', 'Warning max is less then Danger min');
        }
        if($input['warning_max'] < $input['valid_max']){
            $validator->getMessageBag()->add('warning_max', 'Danger max is less then Warning max');
            $validator->getMessageBag()->add('valid_max', 'Warning max is greater then Danger max');
        }

        if(!$validator -> errors() -> count()) {

            $new  = new \Model\TemperaturesProbesAreas();
            $new -> fill($input);
            $new -> unit_id   = $this->auth_user->unit()->id;
            $new -> group_id  = $this->groups[$group];
            $save = $new -> save();
            $type = $save ? 'success' : 'fail';
            $msg  =\Lang::get('/common/messages.create_'.$type);
            return \Redirect::to('/probes/areas')->with($type, $msg);
        }
        else {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function getDelete($id)
    {
        $area = \Model\TemperaturesAreas::find($id);
        if(!$area || !$area -> checkAccess())
            return $this -> redirectIfNotExist();

        if($area->group->identifier == 'probes')
            return \Redirect::back()->with('fail', \Lang::get('/common/messages.delete_fail'));
        $delete = $area -> delete();

        $type = $delete ? 'success' : 'fail';
        $msg  = $delete ? \Lang::get('/common/messages.delete_success') : \Lang::get('/common/messages.delete_fail');

        return \Redirect::back()->with($type, $msg);
    }
}