<?php namespace Sections\LocalManagers;

class Haccp extends LocalManagersSection {

    protected $haccp;


    public function __construct(\Model\Haccp $haccp)
    {
        parent::__construct();
        $this -> haccp = $haccp;
    }

    public function getIndex()
    {
        $haccp = $this -> haccp;
        $individual  = $haccp -> getTreeLevel(1,'individual') -> whereActive(1) -> orderBy('sort','ASC')->get();
        $specific    = $haccp -> getTreeLevel(1,'specific') -> whereActive(1) -> orderBy('sort','ASC')->get();
        $generic     = $haccp -> getTreeLevel(1,'generic') -> whereActive(1) -> orderBy('sort','ASC')->get();
        $user = $this -> auth_user;
        $unit = $user -> unit();
        if($unit->hasOption('hide-haccp-general'))
            $generic = [];
        $this -> breadcrumbs -> addCrumb('haccp/storage', 'Haccp');
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('storage',false) );
        return \View::make($this->regView('index'), compact('breadcrumbs','individual','specific','generic'));
    }

    public function getStorageIndex()
    {
        $haccp = $this -> haccp;
        $user = \Auth::user();
        $data = [];
        foreach(['individual','specific','generic'] as $target)
        {
            switch($target)
            {
                case 'individual' : $targetId = $user -> unit() -> id; break;
                case 'specific'   : $targetId = $user -> unit() -> headquarter -> id; break;
                case 'generic'    : $targetId = 0; break;
            }
            $entities = \Model\Haccp::whereTargetType($target)->whereTargetId($targetId)->orderBy('sort', 'ASC')->get();
            $data[$target] = $haccp -> getTreeFromEntities($entities);
        }

        $unit = $this -> auth_user -> unit();
        if($unit->hasOption('hide-haccp-general'))
            $data['generic'] = [];

        return \View::make($this->regView('storage.index'), compact('data'));
    }

    public function getStorageItem($haccp)
    {
        if(!$haccp || !$haccp -> checkUserAccess())
            return $this -> redirectIfNotExist();
        $parents = $haccp -> getCurrentParents();

        foreach ($parents as $parent) {
            if($parent)
            $this->breadcrumbs->addCrumb('/haccp/storage/item/'.$parent['id'], $parent['title']);
        }
        $this -> breadcrumbs -> addCrumb('haccp/storage', 'Haccp');
        $breadcrumbs = $this -> breadcrumbs -> addLast( $haccp->title());
        return \View::make($this->regView('storage.item'), compact('breadcrumbs','haccp'));
    }

    public function getFormsIndex()
    {
        $assignedForms = new \Model\AssignedForms();
        $navitasForms  = $assignedForms -> getFormsBySelect(5,'generic');
        $unitForms     = $assignedForms -> getFormsBySelect(5,'units');
        $this -> breadcrumbs -> addCrumb('haccp/forms', 'Haccp forms');
        $breadcrumbs = $this -> breadcrumbs -> addLast(  $this -> setAction('Forms list',false) );
        return \View::make($this->regView('forms.index'), compact('unitForms','navitasForms','breadcrumbs'));
    }

    public function getFormsSubmittedList()
    {
        $this -> breadcrumbs -> addCrumb('haccp/forms', 'Haccp forms');
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('Submitted tasks list', false) );
        return \View::make($this->regView('forms.submitted.list'), compact( 'breadcrumbs'));
    }

    public function getFormsSubmittedDetails($id)
    {
        $submitted = \Model\FormsAnswers::with('formLog')->find($id);
        $formHTml = $submitted ?
            \App::make('\Modules\FormBuilder')->getDisplay($submitted->id,'render')
            : '';
        return  \View::make($this->regView('forms.submitted.details'), compact('submitted','formHTml'));
    }

    public function getFormsDatatable()
    {
        if(!\Request::ajax())
            return $this -> redirectIfNotExist();
        $unitId = \Auth::user() -> unit() -> id;
        $answers = \Model\FormsAnswers::whereIn('form_log_id', function($query){
            $query->select('id')->from('forms_logs')->where('assigned_id', 5);
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
                    \HTML::mdOwnOuterBuilder(
                        \HTML::mdOwnButton($answer->id.'/details','haccp/forms','submitted','search')
                    ),
                ];
            }
        return \Response::json(['aaData' => $options]);
    }



}