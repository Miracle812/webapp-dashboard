<?php namespace Sections\Visitors;

class Staff extends VisitorsSection {


    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('staff', 'Staff');
    }

    public function getIndex()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make( $this -> regView('index'), compact('breadcrumbs') );
    }

    public function getTrainings($id){
        $staff = \Model\Staffs::find($id);
        if(!$staff || !$staff -> checkAccess())
            return $this->redirectIfNotExist();

        $this -> breadcrumbs -> addCrumb('/staff/trainings/'.$staff->id, $staff->fullname(), null);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('staff_trainings') );
        return \View::make($this->regView('staff_trainings'), compact('staff','breadcrumbs'));
    }

    public function getDatatable()
    {
        $user = \Auth::user();
        $staff = \Model\Staffs::where('unit_id','=',$user->unit()->id)->get();
        $staff = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $staff) : $staff -> take(100);
        $options = [];
        if($staff) {
            foreach ($staff as $row) {
                $options[] = [
                    strtotime($row->updated_at),
                    $row->updated_at(),
                    $row->fullname(),
                    $row->phone,
                    $row->role,
                    \HTML::ownOuterBuilder(\HTML::ownButton($row->id, 'health-questionnaires','staff-list', 'fa-stethoscope').'<span style="display: inline-block;" class="badge badge-sm up bg-danger count">'.$row->healthQuestionnaires->count().'</span>'),
                    \HTML::ownOuterBuilder(\HTML::ownButton($row->id, 'staff','trainings', 'fa-book').'<span style="display: inline-block;" class="badge badge-sm up bg-danger count">'.$row->trainingsRecords->count().'</span>'),
                ];
            };

            if ($options)
                return \Response::json(['aaData' => $options]);
            else
                return \Response::json(['aaData' => []]);
        }
        else
            return \Response::json(['aaData' => []]);
    }

}