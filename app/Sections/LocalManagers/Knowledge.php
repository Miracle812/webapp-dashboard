<?php namespace Sections\LocalManagers;

class Knowledge extends LocalManagersSection {

    protected $knowledge;
    protected $_root;

    public function __construct(\Model\Knowledges $knowledge)
    {
        parent::__construct();
        $this -> knowledge = $knowledge;
        $this -> _root = $knowledge -> getRoot();
    }

    public function getStorageIndex()
    {
        $knowledge = $this -> knowledge;
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
            $entities = \Model\Knowledges::whereTargetType($target)->whereTargetId($targetId)->orderBy('sort', 'ASC')->get();
            $data[$target] = $knowledge -> getTreeFromEntities($entities);
        }

        $unit = $this -> auth_user -> unit();
        if($unit->hasOption('hide-knowledge-generic'))
            $data['generic'] = [];

        return \View::make($this->regView('storage.index'), compact('data'));
    }

    public function getStorageItem($knowledge)
    {
        if(!$knowledge)
            return $this -> redirectIfNotExist();
        $parents = $knowledge -> getCurrentParents();
        $this -> breadcrumbs -> addCrumb('knowledge/storage', 'Knowledge');
        foreach ($parents as $parent) {
            if($parent)
            $this->breadcrumbs->addCrumb('/knowledge/storage/item/'.$parent['id'], $parent['title']);
        }
        $breadcrumbs = $this -> breadcrumbs -> addLast( $knowledge->title());
        return \View::make($this->regView('storage.item'), compact('breadcrumbs','knowledge'));
    }

    public function getStoragePdf($knowledge)
    {
        if (!$knowledge || !$knowledge->checkUserAccess())
            return $this->redirectIfNotExist();
        return \Services\PdfGenerator::getPdf($knowledge);
    }

    public function getFormsIndex()
    {
        $assignedForms = new \Model\AssignedForms();
        $navitasForms  = $assignedForms -> getFormsBySelect(6,'generic');
        $unitForms     = $assignedForms -> getFormsBySelect(6,'units');
        $this -> breadcrumbs -> addCrumb('knowledge/forms', 'Knowledge forms');
        $breadcrumbs = $this -> breadcrumbs -> addLast(  $this -> setAction('Forms list',false) );
        return \View::make($this->regView('forms.index'), compact('unitForms','navitasForms','breadcrumbs'));
    }

    public function getFormsSubmittedList()
    {
        $this -> breadcrumbs -> addCrumb('knowledge/forms', 'Knowledge forms');
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
            $query->select('id')->from('forms_logs')->where('assigned_id', 6);
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
                    \HTML::mdOwnOuterBuilder(\HTML::mdActionButton($answer->id.'/details','knowledge/forms','submitted','search', 'Search'))
                ];
            }
        return \Response::json(['aaData' => $options]);
    }
}