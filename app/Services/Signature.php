<?php
namespace Services;

class Signature extends \BaseController {

    protected $user;
    protected $signature;

    public function __construct(){
        if(\Auth::check())
        {
            $this->user = \Auth::user();
            $this->signature = $this->user->signature;
        }
    }

     public static function service(){
         return new \Services\Signature();
     }

    public function isSignature(){
        return !empty ($this->signature->signature) ? true : false;
    }

    public function getSignature(){
        return $this->signature->signature;
    }

    public function getUserSignature(){
        return $this->signature;
    }

    public function isPin(){
        return !empty ($this->signature->pin) ? true : false;
    }

    public function isValidPin( $pin ){
        $pin = htmlspecialchars (strip_tags ($pin));
        return $this->signature->pin === $pin ? : false;
    }

    public function rememberSign(  ){

        return \Session::push('sign.remember', 'true');
    }

    public function isRememberSign(  ){
        return \Session::get('sign.remember');
    }

    public function isSignAndRemember(  ){
        return $this->isSignature() && $this->isRememberSign() ? true : false;
    }

    public function forgetSignAuth(  ){
        return \Session::forget('sign.remember');
    }
}