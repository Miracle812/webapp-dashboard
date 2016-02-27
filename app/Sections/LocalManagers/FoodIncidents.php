<?php namespace Sections\LocalManagers;

class FoodIncidents extends LocalManagersSection {

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

    public function getCreate()
    {
        $categories = $this -> categories;
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('create') );
        return \View::make($this->regView('create'), compact('breadcrumbs','categories'));
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

    public function getEdit($id)
    {
        $item = \Model\FoodIncidents::find($id);
        if(!$item || !$item -> checkAccess())
            return $this -> redirectIfNotExist();
        $categories = $this -> categories;
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('edit') );
        return \View::make($this->regView('edit'), compact('breadcrumbs','item','categories'));
    }

    public function postCreate()
    {
        $new   = $this -> fiModel;
        $input = \Input::input();
        $user  = $this -> auth_user;
        $new   -> fill($input);
        $new   -> unit_id = $user -> unitId();
        $new   -> save();
        //\Services\Notifications::createNotification($new->unit_id,'food_incidents',$new->id,['type'=>'new_incident','message'=>'NEW! Food Incident Appear.']);
        return \Redirect::to( '/food-incidents' );
    }

    public function postEdit($id)
    {
        $item = \Model\FoodIncidents::find($id);
        if(!$item || !$item -> checkAccess())
            return $this -> redirectIfNotExist();

        $inputs = \Input::all();
        $item -> fill($inputs);
        $item -> update();
        return \Redirect::to('/food-incidents');
    }


    public function getDelete($id)
    {
        $item = \Model\FoodIncidents::find($id);
        if(!$item || !$item -> checkAccess())
            return $this -> redirectIfNotExist();

        $delete = $item -> delete();
        $type = $delete ? 'success' : 'fail';
        return \Redirect::to('/food-incidents')->with($type, \Lang::get('/common/messages.delete_'.$type));

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
                $item -> s1_i1,
                $item -> s1_t1,
                $item -> s1_s1,
                $item->s1_i3,
                \HTML::mdOwnOuterBuilder(
                    \HTML::mdActionButton($item->id,'food-incidents','details','search','Details').
                    \HTML::mdActionButton($item->id,'food-incidents','edit','edit', 'Edit').
                    \HTML::mdActionButton($item->id,'food-incidents','delete','close','Delete')
                )
            ];
        }
        return \Response::json(['aaData' => $options]);
    }
}