<?php namespace Sections\Visitors;

class OutstandingTasks extends VisitorsSection {

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('outstanding-tasks', 'Outstanding Tasks');
    }

    public function getDatatable()
    {
        $tasks =  $this ->getEntity() ->
            orderBy('created_at','DESC') ->
            get();
        $tasks = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $tasks) : $tasks -> take(100);
        $options = \Services\OutstandingTasks::getDatatable($tasks);
        return \Response::json(['aaData' => $options]);
    }

    public function getEntity()
    {
        return \Model\OutstandingTask::
            where('status', '=', 0) ->
            where('unit_id', '=', $this->auth_user->unitId()) ->
            where(function ($query){
                $query -> where('expiry_date', '>=', \Carbon::now())
                       -> orWhere('expiry_date', '=','0000-00-00 00:00:00');
            });
    }
}