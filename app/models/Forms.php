<?php namespace Model;

class Forms extends Models {

    protected $fillable = ['name','description','active','assigned_id','group_id'];

    public function unit()
    {
        return $this->belongsTo('\Model\Units', 'unit_id');
    }

    public function assigned()
    {
        return $this->belongsTo('\Model\AssignedForms', 'id', 'form_id');
    }

    public function items()
    {
        return $this->hasMany('\Model\FormsItems', 'form_id') -> orderBy('sort','ASC');
    }

    public function group()
    {
        return $this->belongsTo('\Model\FormsGroups', 'group_id');
    }

    public function files()
    {
        $itemsIds = $this->items->lists('id');
        return $this->hasMany('\Model\FormsFiles', 'form_log_id')->whereNull('answer_id')->whereIn('item_log_id',$itemsIds);
    }

    public function delete()
    {
        $this -> files() -> delete();
        $this -> items() -> delete();
        return parent::delete();
    }

    public function getTreeFromDB()
    {
        $items = $this->items;
        $formItems = $this -> _groupByParents($items);
        return $this -> _makeTree($formItems);
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

    protected function _makeTree($items, $parent_id = 0)
    {
        $out = [];
        if(!isset($items[$parent_id])){
             return $out;
        }
        foreach($items[$parent_id] as $item){
            $out[$item->id] = array(
                'page' => $item,
                'children' => $this -> _makeTree($items, $item->id)
            );
        }
        return $out;
    }

    public function groupedRootItems()
    {
        $added[]=$groupedItems=[];
        $x = 0;
        $items = $this -> items() -> where(function ($query) {
            $query -> whereNull('parent_id')
                   -> orWhere('parent_id', 0);
        })->get();

        if($items){
            foreach($items as $key => $item){
                if(in_array($key, $added)){
                    continue;
                }
                if(!$item->parent_id){
                    if($item -> type !== 'tab'){
                        $groupedItems[] = ['item'=>[$item]];
                        $added[] = $key;
                    }
                    elseif($item -> type == 'tab'){
                        $tabs = [];
                        $tabs[] = $item;
                        $added[] = $key;
                        for($i = $key+1; $i<$items->count(); $i++){
                            if($items[$i]->type == 'tab'){
                                $tabs[] = $items[$i];
                                $added[] = $i;
                            }
                            else{
                                break;
                            }
                        }
                        $groupedItems[] = ['tabs'=>$tabs];
                    }
                }
                $x++;
            }
        }
        return $groupedItems;
    }
}