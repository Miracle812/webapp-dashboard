<?php namespace Sections\Visitors;

class Knowledge extends VisitorsSection {

    protected $knowledge;
    protected $_root;

    public function __construct(\Model\Knowledges $knowledge)
    {
        parent::__construct();
        $this -> knowledge = $knowledge;
        $this -> _root = $knowledge -> getRoot();
        $this -> breadcrumbs -> addCrumb('knowledge', 'Knowledge');
    }

    public function getIndex()
    {
        $knowledge = $this -> knowledge;
        $individual  = $knowledge -> getTreeLevel(1,'individual')->whereActive(1)->orderBy('sort','ASC')->get();
        $specific    = $knowledge -> getTreeLevel(1,'specific')->whereActive(1)->orderBy('sort','ASC')->get();
        $generic     = $knowledge -> getTreeLevel(1,'generic')->whereActive(1)->orderBy('sort','ASC')->get();

        $user = $this->auth_user;
        $unit = $user -> unit();
        if($unit->hasOption('hide-knowledge-generic'))
            $generic = [];
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs','individual','specific','generic'));
    }

    public function getItem($knowledge)
    {

        if(!$knowledge || !$knowledge->checkUserAccess())
            return $this -> redirectIfNotExist();
        $parents = $knowledge -> getCurrentParents();

        foreach ($parents as $parent) {
            if($parent)
                $this->breadcrumbs->addCrumb('/knowledge/item/'.$parent['id'], $parent['title']);
        }
        $breadcrumbs = $this -> breadcrumbs -> addLast( $knowledge->title());
        return \View::make($this->regView('item'), compact('breadcrumbs','knowledge'));
    }

    public function getPdf($knowledge)
    {
        if (!$knowledge || !$knowledge->checkUserAccess())
            return $this->redirectIfNotExist();
        return \Services\PdfGenerator::getPdf($knowledge);
    }
}