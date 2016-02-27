<?php namespace Sections\Visitors;

class FoodIncidents extends VisitorsSection {

    public $fiModel;
    public $categories;

    public function __construct(\Model\FoodIncidents $fiModel){
        parent::__construct();
        $this -> fiModel = $fiModel;
        $this -> categories = $fiModel -> categories;
        $this -> breadcrumbs -> addCrumb('food-incidents', 'Food Incidents');

    }

    public function getIndex(){
        $categories = $this -> categories;
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs','categories'));
    }

    public function getDetails($id)
    {
        $item = \Model\FoodIncidents::find($id);
        if(!$item || !$item -> checkAccess())
            return $this->redirectIfNotExist();

        $item -> s1_s1 = $this -> categories[$item -> s1_s1];

        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('details') );
        return \View::make($this->regView('details'), compact('breadcrumbs','item'));
    }

    public function getDatatable(){
        $items = \Model\FoodIncidents::where('unit_id','=',$this->auth_user->unitId())->get();
        $items = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $items) : $items -> take(100);
        $options = [];
        foreach($items as $item){
            if(!$item || !$item -> checkAccess())
               continue;
            $item -> s1_s1 = $this->categories[$item -> s1_s1];
            $options[] = [
                strtotime($item -> created_at),
                $item -> created_at(),
                $item->unit->name,
                $item -> s1_i1,
                $item -> s1_t1,
                $item -> s1_s1,
                $item->s1_i3,
                \HTML::ownOuterBuilder(
                    \HTML::ownButton($item->id,'food-incidents','details','fa-search')
                )
            ];
        }
        return \Response::json(['aaData' => $options]);
    }
}