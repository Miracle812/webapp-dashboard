<?php

class ApiBaseController extends BaseController
{
    public $restful = true;
    public $encryption = false;
    public function __construct ()
    {
        $this->beforeFilter('');
    }

    public function returnJson($data)
    {
        \Config::set('session.driver', 'array');
        \Config::set('cookie.driver', 'array');
        $response = \Response::json($data,200);
        $response -> header('Content-Length',strlen(json_encode($data)));
        $response -> header('Cache-control', 'public, max-age=900');
        //\Log::info($response);
        return $response;
    }
}