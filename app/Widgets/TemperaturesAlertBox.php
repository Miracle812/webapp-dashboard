<?php namespace Widgets;

class TemperaturesAlertBox extends BaseWidget {

    protected $options = [
        'class' => 'col-sm-12',
    ];

    public function render($parameters = array())
    {
        $user = $this->auth_user;
        $folders = \Model\TemperaturesAlertBox::where('unit_id','=',$user->unit()->id) -> whereNull('parent_id')->get();
        return \View::make($this->regView('index'), compact('folders'))->render();
    }
}

