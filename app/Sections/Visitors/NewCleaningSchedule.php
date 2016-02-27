<?php namespace Sections\Visitors;

class NewCleaningSchedule extends VisitorsSection
{

    public function __construct()
    {
        parent::__construct();
        $this->breadcrumbs->addCrumb('new-cleaning-schedule', 'Cleaning Schedule');
    }

    public function getIndex()
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('submitted.list'), compact('breadcrumbs'));
    }

    public function getSubmittedDetails($id)
    {
        $this->breadcrumbs->addCrumb('/new-cleaning-schedule/', 'Submitted list');
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('Submitted task details', false));
        $submitted = \Model\NewCleaningSchedulesSubmitted::find($id);
        if($ansId = $submitted -> form_answer_id){
            $answer = \Model\FormsAnswers::with('formLog')->find($ansId);
            \View::addNamespace('Sections\LocalManagers\FormsManager', app_path() . '/Sections/LocalManagers/FormsManager/views');
            return \View::make('Sections\LocalManagers\FormsManager::common.default.details', compact('answer', 'breadcrumbs'));
        }
        else{
            return \View::make($this->regView('submitted.details'), compact('submitted', 'breadcrumbs'));
        }
    }

    public function getDatatable()
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $unitId = \Auth::user() -> unit() -> id;
        $submitted = \Model\NewCleaningSchedulesSubmitted::whereUnitId($unitId)->get();
        $submitted = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $submitted) : $submitted->take(100);
        $options = [];
        if($submitted)
            foreach ($submitted as $record)
            {
                $title = '<span class="font-bold">'.$record -> title.'</span>';

                if($record -> form_name)
                    $title = $title.('<BR>'.'<small class="text-muted">Form: '.$record -> form_name.'</small>');
                if($record -> staff_name)
                    $title = $title.('<BR>'.'<small class="text-muted">Staff: '.$record -> staff_name.'</small>');
                $details = $record -> form_answer_id ? 'form-details' : 'log-details';
                $options[] = [
                    strtotime($record -> created_at),
                    $record->created_at(),
                    $title,
                    $record->getSchedulesDate(),
                    ($record -> completed ? '<span class="font-bold text-success">Completed</span>' : '<span class="font-bold text-danger">Not Completed</span>'),
                    \HTML::ownOuterBuilder(
                        \HTML::ownButton($record->id.'/'.$details,'new-cleaning-schedule','submitted-details','fa-search')
                    ),
                ];
            }
        return \Response::json(['aaData' => $options]);
    }
}