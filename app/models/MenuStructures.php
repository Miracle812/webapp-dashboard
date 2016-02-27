<?php namespace Model;

class MenuStructures extends Models {

    protected $guarded      = array();

    public $rules = [
        'title'       => 'required|min:5|max:100',
        'menu_title'  => 'required|min:5',
        'type'        => 'required',
        'link'        => '',
        'route_path'  => '',
        'ico'         => '',
    ];

    protected $fillable = ['target_id','target_type','root','lft','rgt','lvl','sort','active','title','menu_title','type','route_path','link','ico','lang'];

    public $types = [
        'module'        => 'Module',
        'link'          => 'Link',
        'first-child'   => 'First Child',
    ];
    public $targetType;
    public function title()
    {
        return nl2br($this->title);
    }

    public function getRoot()
    {
        $targetType = $this -> targetType;
        $lang = \Config::get('app.locale');
        return $this->where('lang', '=', $lang)->whereTitle('ROOT')->whereTargetType($targetType)/*->remember(1)*/->first();
    }

    public function getTreeFromDB()
    {
        $targetType = $this -> targetType;
        $root = $this->getRoot();
        if(!$root){
            $root = $this->createRoot();
        }
        $entities = $this
            ->whereRoot($root->id)
            ->whereTargetType($targetType)
            ->orderBy('sort', 'ASC')/*->remember(1)*/
            ->get();
        $groups = $this->_groupByParents($entities);
        return $this->_makeTree($groups);
    }

    public function namesRouters($query)
    {
        return $query;
    }

    public function createRoot()
    {
        $this -> title       = 'ROOT';
        $this -> menu_title  = 'ROOT';
        $this -> lang        = \Config::get('app.locale');
        $this -> root        = 0;
        $this -> target_id   = 0;
        $this -> target_type = $this -> targetType;
        $this -> lvl         = 0;
        $this -> rgt         = 0;
        $this -> lft         = 0;
        $this -> sort        = 0;
        $this -> active      = 0;

        $this -> save();
        $this -> target_id = $this->id;
        $this -> update();
        return $this;
    }

    protected function _groupByParents($entities)
    {
        $out = array();
        $admin = \Auth::user()->isAdmin();
        foreach($entities as $item){
            if(!$admin && (!$item->active || $item->r_id && !$item->r_active)) continue;
            $target_id = (int)$item->target_id;
            if(!isset($out[ $target_id ])){
                $out[ $target_id ] = [];
            }
            $out[ $target_id ][ $item->id ] = $item;
        }
        return $out;
    }

    protected function _makeTree($groups, $target_id = 0)
    {
        $root = $this->getRoot();
        $target_id = $target_id == 0 ? $root->id : $target_id;
        $out = [];
        if(!isset($groups[$target_id])){
            return $out;
        }
        foreach($groups[$target_id] as $item)
        {
            $out[ $item->id ] = ['page' => $item, 'children' => $this -> _makeTree($groups, $item -> id)];
        }
        return $out;
    }

    public function url()
    {
        return \Illuminate\Support\Facades\URL::to($this->slug);
    }

    public function getPresenter()
    {
        return new \PagePresenter($this);
    }

    public function urlToFirstChild()
    {
        $currentTree = $this->getCurrentTree($this->getTreeFromDB()) ? : false;
        $url = $this->findChildUrl($currentTree);
        return !empty($url) ? $url : false;
    }

    public function urlToPageById()
    {
        $page = $this->where('id', '=', $this->module)->first() ? : false;
        return $page ? $page->url() : false;
    }

    public function hasChildren()
    {
        $page = $this->where('target_id', '=', $this->id)->first() ? : false;
        return $page;
    }

    public function getFirstParent($item = null)
    {
        $thisItem = $item ? : $this;
        $page = $thisItem -> where('id', '=', $thisItem -> target_id) -> first() ? : false;
        return $page;
    }

    public function getCurrentParents($item = null, $arr = [])
    {
        $root = $this->getRoot();
        if($item==null && $this->target_id==$root->id)
            return [];
        $thisItem = $item ? : $this;
        $parent = $thisItem -> where('id', '=', $thisItem -> target_id) -> first() ? : false;
        $result = array_merge([['id' => (string) $parent -> id,'name' => (string) $parent -> title]],$arr);
        if($parent && ($parent->target_id!=$root->id))
            $result = $this->getCurrentParents($parent,$result);
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

    function findChildUrl($array)
    {
        $res = array();
        if(isset($array['page']) && ($array['page'] -> type != 'firstChild'))
           $res = $array['page'] -> url();
        else
            if(isset($array['children']) && !empty($array['children']))
                foreach ($array['children'] as $k => $v)
                    $res = $this->findChildUrl($v);
        return $res;
    }
}