<?php namespace Sections\Admins;

class OptionsMenu extends AdminsSection {

    public function __construct(\Model\OptionsMenu $optionsMenu)
    {
        $this -> section_model = $optionsMenu;
        parent::__construct();
    }

    public function getIndex()
    {
        $threads = $this -> section_model -> whereParentId(0)-> get();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs','threads'));
    }
    
    public function getCreate($targetType, \Model\OptionsMenu $optionsMenu = null)
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create-'.$targetType), compact('breadcrumbs','optionsMenu'));
    }

    public function postCreate($targetType, \Model\OptionsMenu $optionsMenu = null)
    {
        $new        = $this -> section_model;
        $orgIdent   = \Input::get('identifier');
        $input      = \Input::all();
        $rules = $new -> rules ['create'];
        if($targetType == 'option')
            $rules['identifier'] = ['unique:options_menu,identifier,NULL,id,parent_id,'.$optionsMenu->id];
        else
            $rules['identifier'] = ['unique:options_menu,identifier,NULL,id,type,root'];

        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails()) {
            $new -> fill($input);
            $new -> parent_id = $optionsMenu -> id;
            $save = $new -> save();
            $type = $save ? 'success' : 'fail';
            return \Redirect::to('/options-menu')->with($type, \Lang::get('/common/messages.create_'.$type));
        }
        if($validator->fails())
        {
            \Input::merge(['identifier'=> $orgIdent]);
            return \Redirect::back()->withInput()->withErrors($validator);
        }
    }










    public function postCreate55()
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
            return \Redirect::to('/options-menu')->with($type, \Lang::get('/common/messages.create_'.$type));
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


    public function getDelete($id)
    {
        $structure = \Model\MenuStructures::find($id);
        if(!$structure || !$structure->checkAccess())
            return $this -> redirectIfNotExist();

        if($structure->hasChildren()){
            return \Redirect::to('/options-menu')->withErrors(\Lang::get('/common/messages.remove_childrens'));
        }
        else{
            $delete = $structure -> delete();
            $type   = $delete ? 'success' : 'fail';
            return \Redirect::to('/options-menu')->with($type, \Lang::get('/common/messages.delete_'.$type));
        }
    }


}