<?php namespace Sections\LocalManagers;

class FormsManager extends LocalManagersSection
{
    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('forms-manager', 'Forms');
    }

    public function getCreate($id)
    {
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('list') );
        $user = $this -> auth_user;
        $form = \Model\Forms::with('items')->find($id);
        if(!$form || (($unitId = $form -> unit_id) && ($unitId !== $user->unitId())))
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        $this -> setAction('create');
        $view = \Request::ajax() ? 'common.modal.create' : 'create_form';
        return \View::make($this->regView($view), compact('breadcrumbs','form'));
    }

    public function getDisplay($id)
    {
        $form = \Model\Forms::with('items')->find($id);
        if(!$form || !$form -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('Please fill and submit form.',false) );
        return \View::make($this->regView('common.modal.create'), compact('breadcrumbs', 'form'));
    }

    public function getShowAnswer($id)
    {
        $answer = \Model\FormsAnswers::find($id);
        if(!$answer || !$answer -> checkAccess())
            return \Response::json(['type'=>'fail', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        return \View::make($this->regView('common.modal.showanswer'), compact('answer'));
    }

    public function getResolve($idAnswer)
    {
        $target = \Model\FormsAnswers::find($idAnswer);
        if(!$target || !$target -> checkAccess())
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.not_exist')]);
        if($target -> isCompliant())
            return \Response::json(['type'=>'fail', 'msg'=>'Nothing to resolve, form data are compliant.']);

        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('Resolve', false));
        return \View::make($this->regView('common.modal.resolve'), compact('breadcrumbs', 'target'));
    }
}