<?php namespace Api\Pods\Auth;

use Api\Pods\MasterController;

class AlfaController extends MasterController
{
    private $devicesList = ['NavitasHub'];
    public $apiVersion   = 'N/A';
    public function auth() //auth & confirm
    {
        $auth        = \Request::header('Authorization');
        $agent       = \Request::header('User-agent');
        $contentType = \Request::header('Content-type');
        $timestamp   = \Request::header('Timestamp');
        $test        = \Request::header('Test');
        try {
            $notExistText = 'not exist or empty.';
            if (!$agent)
                throw new \Exception("Header: Agent $notExistText");
            if (!$this->isValidDevice($agent))
                throw new \Exception("Device / Agent not supported.");
            if (!$contentType)
                throw new \Exception("Header: Content-type $notExistText");
            if (!$auth)
                throw new \Exception("Header: Authorization $notExistText");
            if (!$timestamp)
                throw new \Exception("Header: Timestamp $notExistText");
            if (!\Mapic::isValidTimestamp($timestamp))
                throw new \Exception("Timestamp invalid. Authorization fail.");
            if (\Carbon::createFromTimestamp($timestamp)->diffInSeconds(\Carbon::now()) > 10)
                throw new \Exception("Token expired. Authorization fail.");
            if(!$test || strtolower($test) !== 'true'){
                if (!$this->isValidAuth($auth,$timestamp))
                    throw new \Exception("Token invalid. Authorization fail.");
            }
        } catch (\Exception $e) {
            return $this->returnJson(['type'=>'error','message' => $e->getMessage()]);
        }
    }

    public function isValidAuth($auth,$timestamp)
    {
        $keyStart  = \Config::get('auth.api.key.start');
        $keyEnd    = \Config::get('auth.api.key.end');
        return $auth ===  base64_encode( md5( $keyStart.$timestamp.$keyEnd ) );
    }
    
    public function isValidDevice($agent)
    {
        list($device,$version) = explode('/',$agent);
        return in_array($device,$this->devicesList);
    }
}