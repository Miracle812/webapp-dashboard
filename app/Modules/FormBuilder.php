<?php namespace Modules;

class FormBuilder extends Modules
{
    protected $options = [];
    public function __construct()
    {
        $this -> activateUserSection();
        $this -> user = \Auth::user();
    }

    public function getComplete($formid,$options=[])
    {
        \Session::get('ref-url') ? : \Session::put('ref-url',\URL::previous());
        $render = isset($options['render']) ? $options['render'] : ($options['render'] = null);
        isset($options['form_id'])  ? $options['form_id'] : ($options['form_id'] = $formid);
        $form = \Model\Forms::with('items')->find($formid);
        $formContent = \FormExt::formGenerator($form,$options);
        $html = $formContent->html;
        $packages = $formContent->packages;
        if($render){
            return \View::make($this->regView('complete'), compact('form','html','packages','render'));
        }
        else {
            $this->layout = \View::make($this->layout);
            $this->layout->content = \View::make($this->regView('complete'), compact('form','html','packages','render'));
        }
    }

    public function getResolve($idAnswer,$options=[])
    {
        $render = isset($options['render']) ? $options['render'] : ($options['render'] = null);
        $formId = isset($options['form_id'])  ? $options['form_id'] : ($options['form_id'] = null);
        $answer = \Model\FormsAnswers::find($idAnswer);
        if(!$answer || !$answer -> checkAccess())
            return \Response::json(['type'=>'error', 'message'=>\Lang::get('/common/messages.not_exist')]);
        if($answer -> isCompliant())
            return \Response::json(['type'=>'error', 'message'=>'Nothing to resolve, form data are compliant.']);
        $form = ($formId ? \Model\Forms::find($formId) : $answer->formLog);
        $html = \FormExt::resolveBuilder($answer,$options);
        if($render){
            return \View::make($this->regView('resolve'), compact('form','answer','html','render'));
        }
        else {
            $this->layout = \View::make($this->layout);
            $this->layout->content = \View::make($this->regView('resolve'), compact('form','answer','html','render'));
        }
    }

    public function getDisplay($id,$type = null)
    {
        $answer = \Model\FormsAnswers::find($id);
        $formContent = \FormExt::displayGenerator($answer);
        $html = $formContent->html;
        $packages = $formContent->packages;
        if($type == 'render'){
            return \View::make($this->regView('display'), compact('answer', 'html', 'packages', 'type'));
        }
        else {
            $this->layout = \View::make($this->layout);
            $this->layout->content = \View::make($this->regView('display'), compact('answer', 'html', 'packages', 'type'));
        }
    }

    public function getUpdate($idAnswer)
    {

    }











}