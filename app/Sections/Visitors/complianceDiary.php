<?php namespace Sections\Visitors;

class ComplianceDiary extends VisitorsSection {

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('compliance-diary', 'Compliance diary');
    }

    public function getIndex(){
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function getDatatable($id = null)
    {
        if (!\Request::ajax())
            return $this->redirectIfNotExist();

        $diary = \Model\ComplianceDiary::
        where('unit_id','=',$this->auth_user->unitId())
            ->where('created_at','<=',date('y-m-d H:i:s'))
            ->orderBy('id')
            ->get();

        $diary = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $diary) : $diary->take(100);

        if (!$diary->count())
            return \Response::json(['aaData' => []]);

        foreach ($diary as $event){

            $title = ($event->staff?$event->staff->fullname() . ' - ' : '') . $event->title;
            $out[] = [
                        '',
                        $event->start,
                        $event->end,
                        $title,
                        $event->description,
                        $this->getClassName($event->type),
                        $event->repeat
                    ];
        }


        return \Response::json(['aaData' => $out]);
    }

    function getClassName($type = 'default'){
        switch($type){
            case 'default'  :return 'default'; ;break;
            case 'high'     :return 'high';  ;break;
            case 'medium'   :return 'medium'; ;break;
            case 'low'      :return 'low'; ;break;
            default: return 'b-l b-2x b-primary'; break;
        }
    }


}