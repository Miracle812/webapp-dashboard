<?php namespace Sections\Admins;

class MenuHq extends AdminsSection {

    public $targetType = 'hq-manager';
    protected $structure;
    protected $_root;
    protected $types;


    public function __construct(\Model\MenuStructures $menuStructure)
    {
        parent::__construct();
        $this -> structure = $menuStructure;
        $menuStructure -> targetType = $this -> targetType;
        $this -> _root     = $menuStructure -> getRoot();
    }

    public function getIndex()
    {
        $structure = $this -> structure;
        $maxLevels = 5;
        $root = $this -> _root;
        $tree = $structure -> getTreeFromDB();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs','structure','maxLevels','root','tree'));
    }
    
    public function getCreate()
    {
        $structure   = new \Model\MenuStructures();
        $types = $structure->types;
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create'), compact('breadcrumbs','types'));
    }

    public function postCreate()
    {
        $input     = \Input::all();
        $new       = new \Model\MenuStructures();
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
            $new  -> target_id   = $root -> id;
            $new  -> target_type = $this -> targetType;
            $new  -> sort       = 1;
            $new  -> active     = 0;
            $root -> rgt        = $root -> rgt + 2;

            $save = $new -> save();

            $type = $save ? 'success' : 'fail';
            return \Redirect::to('/menu-hq')->with($type, \Lang::get('/common/messages.create_'.$type));
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
            $entity = $this->structure->find($item['id']);
            if($entity){
                $entity->lvl =  $root->id == $item['id'] ? 0 : $depth;
                $entity->sort = $sort;
                $entity->target_id = $depth==1 ? $root->id : $parent;
                $entity->update();
            }
            if(isset($item['children'][0]))
                $this->postUpdateOrder($item['children'], $item['id'], $depth);
        }
        return \Response::json(['type' => 'success', 'msg' => 'Update completed']);
    }

    public function getEdit($id)
    {
        $structure = \Model\MenuStructures::find($id);
        if(!$structure || !$structure->checkAccess())
            return $this -> redirectIfNotExist();
        $types = $structure->types;
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('edit'), compact('breadcrumbs','structure','types'));
    }

    public function getIcons()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('icons') );
        return \View::make($this->regView('modal/icons'), compact('breadcrumbs','structure','types'));
    }

    public function postEdit($id)
    {
        $input     = \Input::all();
        $structure = \Model\MenuStructures::find($id);
        if(!$structure || !$structure->checkAccess())
            return $this -> redirectIfNotExist();
        $rules     = $structure -> rules;
        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails())
        {
            $structure -> fill($input);
            $update = $structure -> update();
            $type = $update ? 'success' : 'fail';
            return \Redirect::to('/menu-hq')->with($type, \Lang::get('/common/messages.update_'.$type));
        }
        else
        {
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function getDelete($id)
    {
        $structure = \Model\MenuStructures::find($id);
        if(!$structure || !$structure->checkAccess())
            return $this -> redirectIfNotExist();

        if($structure->hasChildren()){
            return \Redirect::to('/menu-hq')->withErrors(\Lang::get('/common/messages.remove_childrens'));
        }
        else{
            $delete = $structure -> delete();
            $type   = $delete ? 'success' : 'fail';
            return \Redirect::to('/menu-hq')->with($type, \Lang::get('/common/messages.delete_'.$type));
        }
    }

    public function getActive($id)
    {
        $structure = \Model\MenuStructures::find($id);
        if(!$structure || !$structure->checkAccess())
            return $this -> redirectIfNotExist();

        $structure->active = $structure -> active ? 0 : 1;
        if($structure->update()){
            $title = $structure -> active ? 'Enabled' : 'Disabled';
            $data = [
                'i-class'   => $structure -> active ? 'fa fa-check text-success' : 'fa fa-times text-danger',
                'title'     => $title,
                'bg-class'  => ''
            ];
            return \Response::json(['type' => 'info', 'msg' => \Lang::get($title), 'data' => $data]);
        }
        return false;

    }

}