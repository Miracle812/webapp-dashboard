<?php namespace Widgets;

class Calendar extends BaseWidget {

    public function render($parameters = array())
    {
        return \View::make($this->regView('index'));
    }
}