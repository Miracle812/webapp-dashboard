<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/


App::before(function($request)
{
    \Model\Online::updateCurrent();
    $navitasDevice = (\Agent::match('NavitasUpdater') || \Agent::match('NavitasSmartProbe') || \Agent::match('NavitasHub') || \Agent::match('NavitasDroid')) ? true : false;
    if (App::environment('_app') && !$navitasDevice) {
        if(!Request::secure() && $_SERVER["HTTP_X_FORWARDED_PROTO"] != "https") {
            $_SERVER['HTTPS'] = 'on';
            return Redirect::secure(Request::getRequestUri());
        }
        URL::forceSchema('https');
    }
});

App::after(function($request, $response)
{
    if(Auth::check())
    {
        if(!contain(['_debugbar','datatable'], $url = Request::url()))
        {
            $action = (\Session::get('zombie_user_id') ? 'zombie_log_in' : 'log_in');
            $agent  = (\Session::get('zombie_user_id') ? 'zombie_user,'.\Session::get('zombie_user_id') : $_SERVER['HTTP_USER_AGENT']);
            $user   = \Auth::user();
            $role   = $user->role()->name;
            $statsId = \Model\UsersStatistics::firstOrCreate([
                'user_id' => $user->id,
                'session_id' => \Session::getId(),
                'ip' => Request::getClientIp(),
                'agent' => $agent,
                'action' => $action,
                'role' => $role
            ]);
            $method  = Request::method();

            $data = ($method == 'POST' && !Input::file()) ? \Input::all() : NULL;
            if($data){
                if(isset($data['password']))
                    unset($data['password']);
                if(isset($data['password_confirmation']))
                    unset($data['password_confirmation']);
                if(isset($data['signature']))
                    unset($data['signature']);
                if(isset($data['_token']))
                    unset($data['_token']);
                if(isset($data['_token2']))
                    unset($data['_token2']);
            }

            if(Request::ajax() && $method == 'GET'){}
            else{
                \Model\UsersTrackLogs::create(['stats_id' => $statsId->id, 'url' => $url, 'method' => $method, 'ajax' => (Request::ajax() ? 1 : 0), 'data' => $data ? serialize($data) : NULL]);
            }
        }
    }
});

function contain(array $needle, $haystack)
{
    foreach($needle as $key){
        if(strpos($haystack, $key))
            return true;
    }
    return false;
}

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
    if (Auth::check())
        if(Session::has('locked'))
            return Redirect::to('locked');

   // print_r( Route::currentRouteName()); die;
	if (Auth::guest()) {
        Session::put('loginRedirect', Request::url());

        if(Request::ajax()){
            return Response::json(['type'=>'error','message'=>'Unauthorized'],401);
        }

        return Redirect::to('login');
    }

});

Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('login');
});

/*
|--------------------------------------------------------------------------
| Role Permissions
|--------------------------------------------------------------------------
|
| Access filters based on roles.
|
*/

// Check for role on all admin routes
if ($user = Auth::user()) {
    if ($user->hasRole('admin'))
        Entrust::routeNeedsRole('/*', array('admin'), Redirect::to('/'));
    if ($user->hasRole('local-manager'))
        Entrust::routeNeedsRole('/*', array('local-manager'), Redirect::to('/'));
    if ($user->hasRole('area-manager'))
        Entrust::routeNeedsRole('/*', array('area-manager'), Redirect::to('/'));
    if ($user->hasRole('hq-manager'))
        Entrust::routeNeedsRole('/*', array('hq-manager'), Redirect::to('/'));
    if ($user->hasRole('visitor'))
        Entrust::routeNeedsRole('/*', array('visitor'), Redirect::to('/'));
    include('AccessModules.php');
}
/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function() {
    $inputToken = Input::get('_token') ? : Input::get('_token2');
    $token = Request::ajax() ? Request::header('X-CSRF-Token') : $inputToken;
    if (Session::token() != $token) {
        //throw new \Illuminate\Session\TokenMismatchException;
        return Redirect::to('logout');
    }
});

/*
|--------------------------------------------------------------------------
| Language
|--------------------------------------------------------------------------
|
| Detect the browser language.
|
*/

Route::filter('detectLang',  function($lang = 'auto')
{
    if(Auth::check()){
        Config::set('app.locale', Auth::user()->lang);
        App::setLocale(Auth::user()->lang);
    }

    elseif($lang != "auto" && in_array($lang , Config::get('app.available_language')))
    {
        Config::set('app.locale', $lang);
    }

    else
    {
        $browser_lang = !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? strtok(strip_tags($_SERVER['HTTP_ACCEPT_LANGUAGE']), ',') : '';
        $browser_lang = substr($browser_lang, 0,2);
        $userLang = (in_array($browser_lang, Config::get('app.available_language'))) ? $browser_lang : Config::get('app.locale');
        Config::set('app.locale', $userLang);
        App::setLocale($userLang);
    }
});

Route::filter('detectTimezone',  function($timezone = 'auto')
{
    setClientTimezone();
});

Route::filter('api_probes_auth', function($route,$request,$value)
{
    $ver = '\Api\Probes\V'.$value.'\AuthController';
    $controller = new $ver();
    return $controller -> auth();
});

//pods api

Route::filter('api_pods_check_hub_device', function()
{
    $agent = \Request::header('User-Agent');
    $explode = explode('/',$agent);
    try {
        if ($explode[0]!=='NavitasHub')
            throw new Exception('Not allowed device.');
    }
    catch(\Exception $e){
        return Response::json (['type'=>'error','messages' => [$e->getMessage()]],200);
    }
});

Route::filter('api_droid_check_device', function()
{
    $agent = new Agent();
    $agent = \Request::header('User-Agent');
    $explode = explode('/',$agent);
    try {
        if ($explode[0]!=='NavitasDroid')
            throw new Exception('Unauthorized device.');
    }
    catch(\Exception $e){
        return Response::json (['type'=>'error','message' => $e->getMessage()],401);
    }
});

Route::filter('api_pods_auth', function($route,$request,$namespace,$ver,$sub=null)
{
    try{
        if(!\Mapic::checkHubAgentVer($ver))
            throw new Exception('Invalid hub version for this route.');
    }
    catch(\Exception $e){
        return Response::json (['type'=>'error','messages' => [$e->getMessage()]],200);
    }

    $exceptions = [['get'=>'timestamp']];
    if (!checkAuthExceptions($exceptions)){
        $namespace = '\Api\Pods\\'.$namespace.'\AuthController';
        $controller = new $namespace();
        return $controller -> auth();
    }
});







Route::filter('decryption_valid', function()
{
    $hash = Route::input('hash');
    try{
        \Crypt::decrypt($hash);
    }
    catch(\Exception $e){
        return \Redirect::to('/error-page')->with('error', $e->getMessage());
    }
});

Route::filter('api_navitas_auth', function()
{
    $jsonData = Input::json() -> all();

    try{
        if(!isset($jsonData['encProbeId']) || !isset($jsonData['encPin']))
            throw new Exception('Invalid data request.');
    }
    catch(\Exception $e){
        return Response::json (['status'=>'fail','message' => $e->getMessage()],400);
    }

    $encryptedProbeId  = $jsonData['encProbeId'];
    $encryptedPin      = $jsonData['encPin'];
    $encrypter = new MCrypt();

    try{
        $decryptedProbeId   = $encrypter -> decrypt ($encryptedProbeId);
        $decryptedPin       = $encrypter -> decrypt ($encryptedPin);
    }
    catch(\Exception $e){
        return Response::json (['status'=>'fail','message' => $e -> getMessage()],400);
    }

    try{
        $device = \Model\TemperaturesProbesDevices::where ('probe_ident','=',$decryptedProbeId) -> first();
        if(!$device)
            throw new Exception('No data for this id probe.');
        if(!$device->active)
            throw new Exception('Probe may be disabled.');
    }
    catch(\Exception $e){
        return Response::json (['status'=>'fail','message' => $e -> getMessage()],400);
    }

    try{
        if($device -> pin !== $decryptedPin)
            throw new Exception('Invalid auth.');
    }
    catch(\Exception $e){
        return Response::json (['status'=>'fail','message' => $e -> getMessage()],401);
    }
    Navitas::$probe = $device;
});

function checkAuthExceptions($exceptions){
    $data = [];
    foreach($exceptions as $excs)
        foreach($excs as $key => $val)
            if (Request::isMethod($key) && Request::is('api/pod/*/'.$val))
                $data[] = $val;
    return count($data) > 0 ? true : false;
}

function setClientTimezone()
{
    $timezone = Config::get('app.timezone');
    //if(Auth::check()){
    //    $timezone = Auth::user()->timezone;
    // }
    //Config::set('app.timezone', $timezone);
    //date_default_timezone_set($timezone);
}

Route::filter('RefreshToken', function($route, $request, $response)
{
    $token = \JWTAuth::parseToken();
    $response->headers->set('x-access-token', $token->refresh());
});

Route::filter('tokenCheck', function()
{
    if (! $user = JWTAuth::parseToken()->authenticate()) {
        return Response::json(['user_not_found'], 404);
    }
});