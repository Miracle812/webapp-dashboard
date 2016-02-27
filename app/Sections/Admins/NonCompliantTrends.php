<?php namespace Sections\Admins;

class NonCompliantTrends extends AdminsSection
{
    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('non-compliant-trends', 'Non Compliant Trends');
    }

    public function getIndex()
    {
        $menu = \Model\NonCompliantTrends::orderBy('sort')->get();
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make( $this -> regView('menu'), compact('breadcrumbs','menu') );
    }

    public function getDelete($id)
    {
        $item = \Model\NonCompliantTrends::find($id);
        if($item)
            $item->delete();
        return 1;
    }

    public function getCreate()
    {
        $sort = \Model\NonCompliantTrends::all()->count();
        $item = \Model\NonCompliantTrends::create(['sort'=>($sort+1),'name'=>'New Item']);
        return $item->id;
    }

    public function postUpdate()
    {
        $menus = \Input::get();
        foreach($menus as $key => $menu){
            $id = $menu['id'];
            $name = $menu['name'];
            $sort = $key+1;
            $item = \Model\NonCompliantTrends::find($id);
            if($item)
                $item->update(['name'=>$menu['name'],'sort'=>$sort]);
            else
                \Model\NonCompliantTrends::create(['sort'=>$key,'name'=>$name]);
        }
        return \Response::json(['type'=>'success', 'msg'=>'Successful']);
    }

    public function getAnswers()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('Answers',false) );
        return \View::make( $this -> regView('answers'), compact('breadcrumbs') );
    }

    public function getDatatable()
    {
        if (!\Request::ajax())
            return $this->redirectIfNotExist();
        $ots = \Model\OutstandingTask::whereNotNull('trends')->orWhereNotNull('action_todo')->get();
        $options = [];
        if (count($ots)) {
            foreach ($ots as $ot) {
                if($ot->units)
                    $options[] = [
                        strtotime($ot->created_at),
                        $ot->created_at(),
                        $ot->units->name,
                        $ot->target_type,
                        $ot->trends ? : 'N\A',
                        $ot->action_todo,
                    ];
            }
        }
        return \Response::json(['aaData' => $options]);
    }
}