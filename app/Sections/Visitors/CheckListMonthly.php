<?php namespace Sections\Visitors;

class CheckListMonthly extends VisitorsSection {

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('check-list-monthly', 'Monthly check list');
    }

    public function getIndex()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('Submitted tasks list', false) );
        return \View::make($this->regView('index'), compact( 'breadcrumbs'));
    }

    public function getSubmittedDetails($id)
    {
        $answer = \Model\FormsAnswers::with('formLog')->find($id);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('Submitted task details',false) );
        \View::addNamespace ('Sections\LocalManagers\FormsManager', app_path().'/Sections/LocalManagers/FormsManager/views');
        return \View::make('Sections\LocalManagers\FormsManager::common.default.details', compact('answer', 'breadcrumbs'));
    }

    public function getDatatable()
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $unitId = \Auth::user() -> unit() -> id;
        $answers = \Model\FormsAnswers::whereIn('form_log_id', function($query){
            $query->select('id')->from('forms_logs')->where('assigned_id', 3);
        })-> whereUnitId($unitId)->get();
        $answers = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $answers) : $answers -> take(100);
        $options = [];
        if($answers)
            foreach ($answers as $answer)
            {
                $opt = unserialize($answer->options);
                $options[] = [
                    strtotime($answer -> created_at),
                    $answer->created_at(),
                    $answer->formLog->name,
                    isset($opt['compliant']) ? $opt['compliant'] : 'N/A Not implemented',
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton($answer->id,'check-list-monthly','submitted-details','fa-search')
                    ),
                ];
            }
        return \Response::json(['aaData' => $options]);
    }

}