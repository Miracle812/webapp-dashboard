<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/controllers/ajax',
	app_path().'/controllers/api',
    app_path().'/controllers/cron',
    app_path().'/controllers/NaviSockets',
	app_path().'/models',
	app_path().'/Widgets',
	app_path().'/Modules',
    app_path().'/Services',
    app_path().'/library',
    app_path().'/library/Mapic/',
    app_path().'/library/Textlocal/',
    app_path().'/presenters',
    app_path().'/Repositories',
	app_path().'/database/seeds',
));

/*
|--------------------------------------------------------------------------
| Section Directories Scanner
|--------------------------------------------------------------------------
*/

$dir = [];
$directories = [
    'Sections',
    'controllers/Api/Probes',
    'controllers/Api/Pods'
];
foreach($directories as $directory) {
    $pathSection = app_path() . '/'.$directory.'/';
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($pathSection),
        RecursiveIteratorIterator::SELF_FIRST);
    foreach ($iterator as $file)
        if ($file->isDir())
            $dir[] = $file->getRealpath();
}
ClassLoader::addDirectories(array_unique($dir));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a rotating log file setup which creates a new file each day.
|
*/

$logFile = 'log-'.php_sapi_name().'.txt';
Log::useDailyFiles(storage_path().'/logs/'.$logFile);

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
    $pathInfo = Request::getPathInfo();
    $message = $exception->getMessage() ?: 'Exception';
    if ( Request::header('content-type') === 'application/json')
    {
        return Response::json([
            'type' => 'error',
            'message' => $message],
            $code
        );
    }

    if($code == 404){
        if (preg_match('/\.(jpg|jpeg|png|gif|bmp)$/', $pathInfo)){
            $image = public_path().'/assets/images/p0.jpg';
            return Response::download($image);
        }
    }

    Log::error("$code - $message @ $pathInfo\r\n$exception");

    if (Config::get('app.debug')) {
    	return;
    }

    switch ($code)
    {
        case 403:
            $errMsg = 'Sorry, an error (code '.$code.') occurred.  Access Forbidden.'; break;

        case 500:
            $errMsg = 'Sorry, an error (code '.$code.') occurred.  Internal Server Error.'; break;

        default:
            $errMsg = 'Sorry, an error (code '.$code.') occurred.  Page Not Found.'; break;
    }

    if(Request::ajax())
        return Response::json(['type'=>'error', 'msg'=>$errMsg ]);

    switch ($code)
    {
        //case 403:
        //case 500:
        default:
            return Response::view('error/code', compact('code'),$code);
    }
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenace mode is in effect for this application.
|
*/

App::down(function()
{
    return Response::view('error/under_construction',[]);
});

App::missing(function($exception)
{
    if(!Auth::check()) {
        if ( Request::header('content-type') === 'application/json' )
        {
            return Response::json([
                'type' => 'error',
                'message' => '404 Not Found'],
                404
            );
        }
        return Redirect::to('login');
    }
});
/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/
foreach(['filters.php','events.php','UserEvents.php','html_extensions.php'] as $file){
    require app_path().'/'.$file;
}