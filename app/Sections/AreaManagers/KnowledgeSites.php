<?php namespace Sections\AreaManagers;

class KnowledgeSites extends AreaManagersSection {

    protected $knowledge;
    protected $_root;
    public $targetType = 'individual';

    public function __construct(\Model\Knowledges $knowledge)
    {
        parent::__construct();
        $knowledge -> targetType = $this -> targetType;
        $this  -> knowledge = $knowledge;
        $this  -> _root = $knowledge -> getRoot();
        $this -> assignedUnits = \Auth::user()->units;
        $this -> breadcrumbs -> addCrumb('knowledge-sites', 'Knowledge Sites');

    }
    public function postSelectSite()
    {
        $id = \Input::get('site');
        if(is_numeric($id) && in_array($id,\Auth::user()->units->lists('id'))){
            \Session::put('session-nestable-tree-target-id',$id);
        } else{
            \Session::forget('session-nestable-tree-target-id');
        }
        return \Redirect::to('/knowledge-sites');
    }

    public function getIndex()
    {
        $knowledge = $this -> knowledge;
        $maxLevels = 5;
        $root = $this -> _root;
        $tree = $knowledge -> getTreeFromDB();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        $sites = $this -> assignedUnits;
        return \View::make($this->regView('index'), compact('breadcrumbs','sites','knowledge','maxLevels','root','tree'));
    }

    public function getCreate()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create'), compact('breadcrumbs'));
    }

    public function postCreate()
    {
        $input     = \Input::all();
        $new       = new \Model\Knowledges();
        $rules     = $new -> rules;
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails())
        {
            $new -> fill($input);
            $root =  $this -> _root;
            $new  -> lft        = $root -> rgt;
            $new  -> rgt        = $root -> rgt+1;
            $new  -> root       = $root -> id;
            $new  -> lvl        = 1;
            $new  -> parent_id  = $root -> id;
            $new  -> target_type= $root -> target_type;
            $new  -> target_id  = $root -> target_id;
            $new  -> sort       = 0;
            $new  -> active     = 0;
            $save = $new -> save();
            $type = $save ? 'success' : 'fail';
            return \Redirect::to('/knowledge-sites')->with($type, \Lang::get('/common/messages.create_'.$type));
        }
        else
        {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function postUpdateOrder($child = null, $parent = null, $depth = 0)
    {
        ++$depth;
        $sort = 0;
        $sortlist = $child ? : \Input::all();
        $root = $this->_root;
        foreach($sortlist as $item){
            ++$sort;
            $entity = $this->knowledge->find($item['id']);
            if($entity){
                $entity->lvl =  $root->id == $item['id'] ? 0 : $depth;
                $entity->sort = $sort;
                $entity->parent_id = $depth==1 ? $root->id : $parent;
                $entity->update();
            }
            if(isset($item['children'][0]))
                $this->postUpdateOrder($item['children'], $item['id'], $depth);
        }
        return \Response::json(['type' => 'success', 'msg' => 'Update completed']);
    }

    public function getEdit($knowledge)
    {
        if(!$knowledge || !$knowledge->checkAccess())
            return $this -> redirectIfNotExist();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('edit'), compact('breadcrumbs','knowledge'));
    }

    public function postEdit($knowledge)
    {
        $input     = \Input::all();
        if(!$knowledge || !$knowledge->checkAccess())
            return $this -> redirectIfNotExist();
        $rules     = $knowledge -> rules;
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails())
        {
            $knowledge -> fill($input);
            $update = $knowledge -> update();
            $type = $update ? 'success' : 'fail';
            return \Redirect::to('/knowledge-sites')->with($type, \Lang::get('/common/messages.update_'.$type));
        }
        else
        {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function getDelete($knowledge)
    {
        if(!$knowledge || !$knowledge->checkAccess())
            return $this -> redirectIfNotExist();

        if($knowledge->hasChildren()){
            return \Redirect::to('/knowledge-sites')->withErrors(\Lang::get('/common/messages.remove_childrens'));
        }
        else{
            $delete = $knowledge -> delete();
            $type   = $delete ? 'success' : 'fail';
            return \Redirect::to('/knowledge-sites')->with($type, \Lang::get('/common/messages.delete_'.$type));
        }
    }

    public function getActive($knowledge)
    {
        if(!$knowledge || !$knowledge->checkAccess())
            return $this -> redirectIfNotExist();

        $knowledge->active = $knowledge -> active ? 0 : 1;
        if($knowledge->update()){
            $title = $knowledge -> active ? 'Enabled' : 'Disabled';
            $data = [
                'i-class'   => $knowledge -> active ? 'fa fa-check text-success' : 'fa fa-times text-danger',
                'title'     => $title,
                'bg-class'  => ''
            ];
            return \Response::json(['type' => 'info', 'msg' => \Lang::get($title), 'data' => $data]);
        }
        return false;
    }
}