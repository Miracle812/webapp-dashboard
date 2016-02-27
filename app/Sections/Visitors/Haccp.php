<?php namespace Sections\Visitors;

class Haccp extends VisitorsSection {

    protected $haccp;
    protected $_root;

    public function __construct(\Model\Haccp $haccp)
    {
        parent::__construct();
        $this -> haccp = $haccp;
        $this -> _root = $haccp -> getRoot();
        $this -> breadcrumbs -> addCrumb('haccp', 'Haccp');
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

        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        return \View::make($this->regView('index'), compact('breadcrumbs','individual','specific','generic'));
    }

    public function getItem($haccp)
    {
        if(!$haccp || !$haccp -> checkUserAccess())
            return $this -> redirectIfNotExist();
        $parents = $haccp -> getCurrentParents();

        foreach ($parents as $parent) {
            if($parent)
                $this->breadcrumbs->addCrumb('/haccp/item/'.$parent['id'], $parent['title']);
        }
        $breadcrumbs = $this -> breadcrumbs -> addLast( $haccp->title());
        return \View::make($this->regView('item'), compact('breadcrumbs','haccp'));
    }
}