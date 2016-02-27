<?php namespace Sections\LocalManagers;

class RatingStars extends LocalManagersSection {

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('rating-stars', 'Rating starts');
    }

    public function getEdit(){

    }

    public function getEditModal()
    {
        $user = \Auth::user();
        $unit = $user -> unit();
        $rating = $unit->rating();
        return \View::make($this->regView('modal_edit'), compact('rating'));
    }

    public function postEdit(){

    }
}