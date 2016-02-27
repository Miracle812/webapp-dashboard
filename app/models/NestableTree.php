<?php namespace Model;

class NestableTree extends Models {


    public function title() {
        $title = $this -> title == "ROOT" ? $this -> target_type : $this -> title;
        return ucfirst($title);
    }

    public function getTreeFromDB($roles = null)
    {
        $root = $this->getRoot($roles);
        if(!$root){
            $root = $this->createRoot($roles);
        }
        $entities = $this::
        whereLang($root -> lang) ->
        whereTargetType($root -> target_type) ->
        whereTargetId($root -> target_id) ->
        orderBy('sort', 'ASC') ->
        get();
        $groups = $this -> _groupByParents($entities);
        return $this -> _makeTree($groups);
    }

    public function getRoot($roles = null)
    {
        $user = \Auth::user();
        return $this ->
        whereLang($user -> lang) ->
        whereTargetType($this -> targetType) ->
        whereTargetId($this -> getTargetIdByRole($roles)) ->
        where('title', '=', 'ROOT') ->
        first();
    }
    public function createRoot($roles = null)
    {
        $user = \Auth::user();
        $this -> title = 'ROOT';
        $this -> target_type = $this -> targetType;
        $this -> target_id   = $this -> getTargetIdByRole($roles);
        $this -> parent_id   = 0;
        $this -> lang        = $user -> lang;
        $this -> active = 1;
        $this -> save();
        return $this;
    }

    public function getTargetIdByRole($roles = null)
    {
        $user = \Auth::user();
        $roles = $roles ? $roles : $user -> roles -> lists('name');
        $targetType = $this -> targetType;
        switch ($roles){
            case in_array('admin',$roles):
                $targetId = 0; break;
            case in_array('hq-manager',$roles):
                $targetId = $user->headquarter()->id; break;
            case in_array('area-manager',$roles):
                $targetId = ($targetType == 'individual') ? ( \Session::get('session-nestable-tree-target-id') ? : 0 ) : $user->headquarter()->id; break;
            case in_array('local-manager',$roles):
                $targetId = $user->unit()->id; break;
            case in_array('visitor',$roles):
                $targetId = $user->unit()->id; break;
            default:
                $targetId = 0; break;
        }
        return $targetId;
    }

    protected function _groupByParents($entities)
    {
        $out = array();
        foreach($entities as $item){
            $parent_id = (int)$item -> parent_id;
            if(!isset($out[ $parent_id ])){
                $out[ $parent_id ] = array();
            }
            $out[ $parent_id ][ $item->id ] = $item;
        }
        return $out;
    }

    public function getTreeFromEntities($entities)
    {
        $groups = $this -> _groupByParents($entities);
        return $this -> _makeTree2($groups);
    }

    protected function _makeTree2($groups, $parent_id = 0)
    {

        $out = [];
        if(!isset($groups[$parent_id])){
            return $out;
        }
        foreach($groups[$parent_id] as $item){
            if(!$item->root) //root record
                return $this -> _makeTree2($groups, $item->id);
            $out[$item->id] = array(
                'name'=> $item->title,
                'children' => $this -> _makeTree2($groups, $item->id)
            );
        }
        return $out;
    }

    protected function _makeTree($groups, $parent_id = 0)
    {

        $out = [];
        if(!isset($groups[$parent_id])){
            return $out;
        }
        foreach($groups[$parent_id] as $item){
            if(!$item->root) //root record
                return $this -> _makeTree($groups, $item->id);
            $out[$item->id] = array(
                'page' => $item,
                'children' => $this -> _makeTree($groups, $item->id)
            );
        }
        return $out;
    }

    public function urlToFirstChild()
    {
        $currentTree = $this->getCurrentTree($this->getTreeFromDB()) ? : false;
        $url = $this->findChildUrl($currentTree);
        return !empty($url) ? $url : false;
    }

    public function hasChildren()
    {
        $item = $this->where('parent_id', '=', $this->id)->first() ? : false;
        return $item;
    }

    public function childrens()
    {
        return $this::whereParentId($this->id)->get();
    }

    public function getFirstParent($item = null)
    {
        $thisItem = $item ? : $this;
        $page = $thisItem -> where('id', '=', $thisItem -> parent_id) -> first() ? : false;
        return $page;
    }

    public function getCurrentParents($item = null, $arr = [])
    {

        if(!$item && !$this->parent_id)
            return [];

        $thisItem = $item ? : $this;

        $parent = $thisItem -> where('id', '=', $thisItem -> parent_id) -> first() ? : false;

        if(!$parent)
            return [];
        $result = array_merge([['id' => $parent -> id,'title' => $parent -> title() ]],$arr);

        if($parent && $parent->parent_id){
            $result = $this->getCurrentParents($parent,$result);
        }
        return $result;
    }

    public function getCurrentTree(array $array)
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveArrayIterator($array),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $key => $item) {
            if (is_array($item) && $key === $this->id) {
                return $item;
            }
        }
        return false;
    }

    public function getTreeLevel($lvl,$target){

        $user = \Auth::user();

        switch($target) {
            case 'individual' : $targetId = $user -> unit() -> id; break;
            case 'specific'   : $targetId = $user -> unit() -> headquarter -> id; break;
            case 'generic'    : $targetId = 0; break;
        }

        return $this::whereLvl($lvl)->whereTargetType($target)->whereTargetId($targetId);
    }

    function findChildUrl($array){
        $res = array();
        if(isset($array['page']) && ($array['page']->type != 'firstChild'))
            $res = $array['page']->url();
        else
            if(isset($array['children']) && !empty($array['children']))
                foreach ($array['children'] as $k => $v)
                    $res = $this->findChildUrl($v);
        return $res;
    }

    public function checkAccess()
    {
        $user = \Auth::user();
        if ( $user -> hasRole('local-manager') || $user -> hasRole('visitor') )
            return $this -> target_id == $user -> unit() -> id;
        elseif ( $user -> hasRole('hq-manager') ) {
            return $this -> target_id == $user -> headquarter() -> id;
        }
        else
            return true; //as admin
    }

    public function checkUserAccess()
    {
        $user = \Auth::user();
        $target = $this -> target_type;

        switch($target) {
            case 'individual' : $targetId = $user -> unit() -> id; break;
            case 'specific'   : $targetId = $user -> unit() -> headquarter -> id; break;
            case 'generic'    : $targetId = 0; break;
        }
        return $this->target_id == $targetId;
    }
}