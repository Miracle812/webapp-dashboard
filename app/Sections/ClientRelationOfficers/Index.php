<?php namespace Sections\ClientRelationOfficers;
class Index extends BaseSection {

    public function getIndex()
    {
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('Dashboard', false));
        $this->layout->content = \View::make($this->regView('index'), compact('breadcrumbs'));
    }
}
