<?php namespace Api\Droid;

class DroidController extends \Controller
{
    public $restful = true;
    private $devicesList = ['NavitasDroid'];

    public function __construct ()
    {
        $this->beforeFilter('');
        $agent       = \Request::header('User-agent');
        $contentType = \Request::header('Content-type');

        try {
            $notExistText = 'not exist or empty.';
            if (!$agent)
                throw new \Exception("Header: Agent $notExistText");
            if (!$this->isValidDevice($agent))
                throw new \Exception("Device / Agent not supported.");
            if (!$contentType)
                throw new \Exception("Header: Content-type $notExistText");
        } catch (\Exception $e) {
            return $this->returnJson(['status' => 'error', 'messages' => $e->getMessage()]);
        }
    }

    public function isValidDevice($agent)
    {
        list($device) = explode('/',$agent);
        return in_array($device,$this->devicesList);
    }

    public function returnJson($data)
    {
        \Config::set('session.driver', 'array');
        \Config::set('cookie.driver', 'array');
        $response = \Response::json($data,200);
        $response -> header('Content-Length',strlen(json_encode($data)));
        $response -> header('Cache-control', 'public, max-age=900');
        \Log::info($response);
        return $response;
    }
}