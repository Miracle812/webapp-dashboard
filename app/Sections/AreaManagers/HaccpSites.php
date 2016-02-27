<?php namespace Sections\AreaManagers;

class HaccpSites extends AreaManagersSection {

    protected $haccp;
    protected $_root;
    public $targetType = 'individual';

    public function __construct(\Model\Haccp $haccp)
    {
        parent::__construct();
        $haccp -> targetType = $this -> targetType;
        $this  -> haccp = $haccp;
        $this  -> _root = $haccp -> getRoot();
        $this -> assignedUnits = \Auth::user()->units;
        $this -> breadcrumbs -> addCrumb('haccp-sites', 'Haccp Sites');
    }

    public function postSelectSite()
    {
        $id = \Input::get('site');
        if(is_numeric($id) && in_array($id,\Auth::user()->units->lists('id'))){
            \Session::put('session-nestable-tree-target-id',$id);
        } else{
            \Session::forget('session-nestable-tree-target-id');
        }
        return \Redirect::to('/haccp-sites');
    }

    public function getIndex()
    {
        $haccp = $this -> haccp;
        $maxLevels = 5;
        $root = $this -> _root;
        $tree = $haccp -> getTreeFromDB();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        $sites = $this -> assignedUnits;
        return \View::make($this->regView('index'), compact('breadcrumbs','sites','haccp','maxLevels','root','tree'));
    }

    public function getCreate()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create'), compact('breadcrumbs'));
    }

    public function postCreate()
    {
        $input     = \Input::all();
        $new       = new \Model\Haccp();
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
            return \Redirect::to('/haccp-sites')->with($type, \Lang::get('/common/messages.create_'.$type));
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
            $entity = $this->haccp->find($item['id']);
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

    public function getEdit($id)
    {
        $haccp = \Model\Haccp::find($id);
        if(!$haccp || !$haccp->checkAccess())
            return $this -> redirectIfNotExist();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('edit'), compact('breadcrumbs','haccp'));
    }

    public function postEdit($id)
    {
        $input     = \Input::all();
        $haccp = \Model\Haccp::find($id);
        if(!$haccp || !$haccp->checkAccess())
            return $this -> redirectIfNotExist();
        $rules     = $haccp -> rules;
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails())
        {
            $haccp -> fill($input);
            $update = $haccp -> update();
            $type = $update ? 'success' : 'fail';
            return \Redirect::to('/haccp-sites')->with($type, \Lang::get('/common/messages.update_'.$type));
        }
        else
        {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function getDelete($id)
    {
        $haccp = \Model\Haccp::find($id);
        if(!$haccp || !$haccp->checkAccess())
            return $this -> redirectIfNotExist();

        if($haccp->hasChildren()){
            return \Redirect::to('/haccp-sites')->withErrors(\Lang::get('/common/messages.remove_childrens'));
        }
        else{
            $delete = $haccp -> delete();
            $type   = $delete ? 'success' : 'fail';
            return \Redirect::to('/haccp-sites')->with($type, \Lang::get('/common/messages.delete_'.$type));
        }
    }

    public function getDatatable($refresh=null)
    {
        $refresh = $refresh ? 'true' : 'false';
        $pages = new \Model\Haccp();
        $pageItems = $pages->getTreeFromDB();
        $first = true;

        return \View::make('Sections\AreaManagers\HaccpCompany::nestable_tree', compact('pageItems','first','refresh'));
    }

    public function getActive($id)
    {
        $haccp = \Model\Haccp::find($id);
        if(!$haccp || !$haccp->checkAccess())
            return $this -> redirectIfNotExist();

        $haccp->active = $haccp -> active ? 0 : 1;
        if($haccp->update()){
            $title = $haccp -> active ? 'Enabled' : 'Disabled';
            $data = [
                'i-class'   => $haccp -> active ? 'fa fa-check text-success' : 'fa fa-times text-danger',
                'title'     => $title,
                'bg-class'  => ''
            ];
            return \Response::json(['type' => 'info', 'msg' => \Lang::get($title), 'data' => $data]);
        }
        return false;
    }
}