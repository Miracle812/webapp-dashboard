<?php namespace Sections\visitors;

class HealthQuestionnaires extends VisitorsSection
{

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('health-questionnaires', 'Health Questionnaires');
    }

    public function getIndex()
    {
        $staff = \Model\Staffs::where('unit_id', '=', \Auth :: user() -> unit() -> id)-> orderBy('id','DESC');
        $breadcrumbs = $this -> breadcrumbs -> addLast( 'Staff List' );
        return \View::make($this->regView('index'), compact('staff', 'breadcrumbs'));
    }

    public function getStaffList($id)
    {
        $staff = \Model\Staffs::find($id);
        $this-> breadcrumbs -> addCrumb('/health-questionnaires/submitted-list', 'Submitted forms');
        $breadcrumbs = $this -> breadcrumbs -> addLast( $staff->fullname() );
        return \View::make($this->regView('staff_list'), compact('staff', 'breadcrumbs'));
    }

    public function getSubmittedList()
    {
        $unitId = \Auth::user() -> unit() -> id;
        $formsLogsId = \Model\FormsLogs::whereAssignedId(1)->lists('id');
        $forms = \Model\FormsAnswers::with('formLog')->whereIn('form_log_id',$formsLogsId) -> whereUnitId($unitId) -> get();
        if(!$forms->count())
            return \Redirect::to('/health-questionnaires/')-> withInfo(['Sorry, no records found. :(']);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('Submitted forms list', false) );
        return \View::make($this->regView('submitted.list'), compact('forms', 'breadcrumbs'));
    }

    public function getSubmittedDetails($id)
    {
        $answer = \Model\FormsAnswers::with('formLog')->find($id);
        $this-> breadcrumbs -> addCrumb('/health-questionnaires/submitted/', 'Submitted list');
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('Submitted form details',false) );
        \View::addNamespace ('Sections\LocalManagers\FormsManager', app_path().'/Sections/LocalManagers/FormsManager/views');
        return \View::make('Sections\LocalManagers\FormsManager::common.default.details', compact('answer', 'breadcrumbs'));
    }

    public function getStaffDatatable($id)
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();

        $staff = \Model\Staffs::with('healthQuestionnaires')->find($id);
        if(!$staff || !$staff -> checkAccess())
            return \Response::json(['aaData' => []]);

        $forms = $staff -> healthQuestionnaires;
        if(!$forms -> count())
            return \Response::json(['aaData' => []]);

        $forms = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $forms) : $forms -> take(100);
        foreach ($forms as $form)
        {
            $options[] = [
                strtotime($form -> created_at),
                $form->created_at(),
                $staff->fullname(),
                $form->form_log->name,
                \HTML::ownOuterBuilder(
                    \HTML::ownButton($form->id,'health-questionnaires','submitted-details','fa-search')
                )
            ];
        }
        return \Response::json(['aaData' => $options]);
    }

    public function getDatatable()
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $unitId = \Auth::user() -> unit() -> id;
        $answers = \Model\FormsAnswers::whereIn('form_log_id', function($query){
            $query->select('id')->from('forms_logs')->where('assigned_id', 1);
        })-> whereUnitId($unitId)->get();
        $answers = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $answers) : $answers -> take(100);
        $options = [];
        foreach ($answers as $answer)
        {
            $staff = \Model\Staffs::find($answer->target_id);
            $options[] = [
                strtotime($answer -> created_at),
                $answer->created_at(),
                $staff?$staff->fullname():'N/A Not Assigned',
                $answer->formLog->name,
                \HTML::ownOuterBuilder(
                    \HTML::ownButton($answer->id,'health-questionnaires','submitted-details','fa-search')),
            ];
        }
        return \Response::json(['aaData' => $options]);
    }

}