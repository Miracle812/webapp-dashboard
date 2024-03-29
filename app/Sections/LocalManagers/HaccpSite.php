<?php namespace Sections\LocalManagers;

use Services\Breadcrumbs;

class HaccpSite extends LocalManagersSection {

    protected $haccp;
    protected $_root;
    public $targetType = 'individual';


    public function __construct(\Model\Haccp $haccp)
    {
        parent::__construct();
        $haccp -> targetType = $this -> targetType;
        $this  -> haccp = $haccp;
        $this  -> _root = $haccp -> getRoot();
        $this -> breadcrumbs -> addCrumb('site-haccp', 'Haccp Site');

    }

    public function getIndex()
    {
        $haccp = $this -> haccp;
        $maxLevels = 5;
        $root = $this -> _root;
        $tree = $haccp -> getTreeFromDB();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs','haccp','maxLevels','root','tree'));
    }

    public function getCreate()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create'), compact('breadcrumbs'));
    }

    public function postCreate()
    {
        $input     = \Input::all();
        $new       = $this -> haccp;
        $rules     = $new -> rules;
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails())
        {
            $new -> fill($input);
            $root =  $this      -> _root;
            $new  -> lft        = $root -> rgt;
            $new  -> rgt        = $root -> rgt+1;
            $new  -> root       = $root -> id;
            $new  -> lvl        = 1;
            $new  -> parent_id  = $root->id;
            $new  -> target_type= $this->targetType;
            $new  -> target_id  = $new -> getTargetIdByRole();
            $new  -> sort       = 0;
            $new  -> active     = 0;
            $save = $new -> save();
            $type = $save ? 'success' : 'fail';
            return \Redirect::to('/site-haccp/')->with($type, \Lang::get('/common/messages.create_'.$type));
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

    public function getEdit($haccp)
    {
        if(!$haccp || !$haccp->checkAccess())
            return $this -> redirectIfNotExist();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('edit'), compact('breadcrumbs','haccp'));
    }

    public function postEdit($haccp)
    {
        $input     = \Input::all();
        if(!$haccp || !$haccp->checkAccess())
            return $this -> redirectIfNotExist();
        $rules     = $haccp -> rules;
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails())
        {
            $haccp -> fill($input);
            $update = $haccp -> update();
            $type = $update ? 'success' : 'fail';
            return \Redirect::to('/site-haccp/')->with($type, \Lang::get('/common/messages.update_'.$type));
        }
        else
        {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function getDelete($haccp)
    {
        if(!$haccp || !$haccp->checkAccess())
            return $this -> redirectIfNotExist();

        if($haccp->hasChildren()){
            return \Redirect::to('/site-haccp/')->withErrors(\Lang::get('/common/messages.remove_childrens'));
        }
        else{
            $delete = $haccp -> delete();
            $type   = $delete ? 'success' : 'fail';
            return \Redirect::to('/site-haccp/')->with($type, \Lang::get('/common/messages.delete_'.$type));
        }
    }

    public function getActive($haccp)
    {
        if(!$haccp || !$haccp->checkAccess())
            return $this -> redirectIfNotExist();

        $haccp->active = $haccp -> active ? 0 : 1;
        if($haccp->update()){
            $title = $haccp -> active ? 'Enabled' : 'Disabled';
            $data = [
                'icon'   => $haccp -> active ? 'check' : 'close',
                'title'     => $title,
                'bg-class'  => $haccp -> active ? 'md-icon material-icons uk-text-success' : 'md-icon material-icons uk-text-danger'
            ];
            return \Response::json(['type' => 'info', 'msg' => \Lang::get($title), 'data' => $data]);
        }
        return false;
    }
}