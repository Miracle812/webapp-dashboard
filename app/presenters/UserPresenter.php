<?php

use Robbo\Presenter\Presenter;

class UserPresenter extends Presenter
{

    public function isActivated()
    {
        if( $this->confirmed )
        {
            return false;
        }
        else
        {
            return true;
        }

    }

}