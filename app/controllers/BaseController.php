<?php
use \Firebase\Firebase;
class BaseController extends Controller {

    public $section;
    public $section_model = NULL;
    public $area;

    public function __construct()
    {
        $this->beforeFilter('csrf', array('on' => 'post'));
        if(\Auth::check()) {
            $this->auth_user = \Auth::user();
            $this->firebase = Firebase::initialize(\Config::get('services.firebase.base_url'), \Config::get('services.firebase.token'));
        }
    }

    public static function call()
    {
        $fullClassName = get_called_class();
        return new $fullClassName();
    }

    public function regView ($view)
    {
        $namespace = get_called_class();
        $reflectionClass = new ReflectionClass (get_called_class());
        $sn = $reflectionClass -> getShortName();
        $fn = $reflectionClass -> getFileName();
        $path = dirname ($fn);
        View::addNamespace ($namespace, $path.'/'.$sn.'/views');
        return $namespace.'::'.$view;
    }

    public function ajaxErrors($errors, $attr)
    {
        $out = [];
        foreach($errors as $key => $error){
            if (\Lang::has($error[0])){
                $out[$key][] = \Lang::get($error[0]);
            }
            elseif (\Lang::has('common/'.$error[0])){
                $out[$key][] = \Lang::get('common/'.$error[0], isset($attr[$key])?$attr[$key]:[]);
            }
            else
                $out[$key][] = $error[0];
        }
        return $out;
    }

    public function getUnitId()
    {
        return \Auth::user()->unit()->id;
    }

    public function getUnitsId()
    {
        $user = \Auth::user();
        if($user->hasRole('hq-manager')) {
            $this->unitsId = \Session::has('unit_id') ? [\Session::get('unit_id')] : $this->getHqUnitsId();
            return $this->unitsId;
        }
        else{
            return [$this->getUnitId()];
        }
    }

    public function getHqUnitsId()
    {
        return \Auth::user() -> headquarter() -> getUnitsId();
    }

    public function getSectionUrl($data = [], $fullPath = false)
    {
        $section = $this -> section_model ?  str_replace('_','-',$this -> section_model -> getTable()) : $this -> section;
        $controller = isset($data['module']) ? $data['module'] : $section;
        $action =  isset($data['action']) ? $data['action'] : '';
        $url = '/'.$this->area.'/'.$controller.'/'.$action;
        return $fullPath ? \URL::to($url) : $url;
    }

    public function getModuleUrl($path = null, $module = null)
    {
        $ms = '/'; //module name separator
        $route = $s = '/'; //url separator

        if(\Auth::check())
            $route = $s.\Auth::user() -> route();

        if(!$module){
            $module = $this -> section;
            if(!$module){
                $rc = new ReflectionClass (get_called_class());
                $module = snake_case($rc -> getShortName(),$ms);
            }
        }
        if($module)
            $route = $route . $s . $module;

        if($path)
            $route = $route . $s . $path;

        return  \URL::to($route);
    }

    public  function setAction($action, $lang = true)
    {
        $lang = $lang ? \Lang::get('/common/actions.'.$action) : $action;
        \View::share('actionName',     $lang);
        return $lang;
    }

    public  function redirectIfNotExist(){
        return \Redirect::back() -> withErrors([\Lang::get('/common/messages.not_exist')]);
    }
}