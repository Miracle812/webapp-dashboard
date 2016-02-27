<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/** ------------------------------------------
 *  Route model binding
 *  ------------------------------------------
 */
Route::model('user',        'User');
Route::model('role',        'Role');
Route::model('haccp',            '\Model\Haccp');
Route::model('podsArea',         '\Model\TemperaturesPodsAreas');
Route::model('knowledge',        '\Model\Knowledges');
Route::model('thread',           '\Model\Messages');
Route::model('staff',            '\Model\Staffs');
Route::model('optionsMenu',      '\Model\OptionsMenu');
Route::model('checkListActions', '\Model\CheckListActions');

/** ------------------------------------------
 *  Route constraint patterns
 *  ------------------------------------------
 */
Route::pattern('haccp',      '[0-9]+');
Route::pattern('checkListActions','[0-9]+');
Route::pattern('knowledge',  '[0-9]+');
Route::pattern('comment',    '[0-9]+');
Route::pattern('post',       '[0-9]+');
Route::pattern('user',       '[0-9]+');
Route::pattern('role',       '[0-9]+');
Route::pattern('token',      '[0-9a-z]+');
Route::pattern('staff',      '[0-9a-z]+');
Route::pattern('id',         '\d+');
Route::pattern('thread',     '\d+');
Route::pattern('optionsMenu','\d+');
Route::pattern('agroup',     'freezers|fridges|probes');


Route::any('test', function() {

    return e(Form::text('first_name'));

});



Route::get('/login-restore-redirect','Sections\Front\Index@getLoginRedirectBack');
Route::get('/login',    'Sections\Front\Index@getLogin');
Route::post('/login',   'Sections\Front\Index@postLogin');
Route::get('/lock-me',  'Sections\Front\Index@getLockMe');
Route::post('/lock-me', 'Sections\Front\Index@postLockMe');
Route::get('/locked',   'Sections\Front\Index@getLocked');
Route::post('/locked',  'Sections\Front\Index@postLocked');
Route::get('/logout/check-outstanding-tasks', 'Sections\Front\Index@getCheckOutstandingTasks');
Route::get('/logout',   'Sections\Front\Index@getLogout');
Route::get('/account-confirmation/{token}', 'Sections\Front\Index@getConfirm');
Route::get('/confirmation/', 'Sections\Front\Index@getConfirmation');
Route::get('/token-access/{username}/{token}', 'Sections\Front\Index@getVisitorLogin');
Route::post('/forgot-password', 'Sections\Front\Index@postForgot');
Route::get('/reset-password/{token}', 'Sections\Front\Index@getReset');
Route::post('/reset', 'Sections\Front\Index@postReset');

Route::group(array('prefix' => 'messages-access'), function () {
    Route::get('{hash}', array('before' => "decryption_valid", 'uses' => 'Sections\Front\Messages@getRedirect'));
    Route::group(array('prefix' => 'thread'), function () {
        Route::get('{thread}', array('before' => "temporary_session", 'uses' => 'Sections\Front\Messages@getThread'));
        Route::post('{thread}', array('before' => "temporary_session", 'uses' => 'Sections\Front\Messages@postThread'));
    });
});

Route::group(array('prefix' => 'messages-system','namespace' => 'Widgets', 'before' => 'auth|detectLang|detectTimezone'), function ()
{
    $ctrl = 'MessagesSystem';
    Route::get ('/live-update/grouped-counter',          $ctrl.'@getLiveUpdateGroupedCounter');
    Route::get ('/live-update/grouped-messages',         $ctrl.'@getLiveUpdateGroupedMessages');
    Route::get ('/live-update/mark-read/{id}',           $ctrl.'@getMarkRead');
    Route::get ('/live-update/dialog-update/{id}',       $ctrl.'@getDialogUpdate');
    Route::get ('/add-recipients/{msgId}',               $ctrl.'@getAddRecipients');
    Route::post('/add-recipients/{msgId}',               $ctrl.'@postAddRecipients');
    Route::controller('/',$ctrl);
});

include('Modules.php');

Route::get('/confirm-delete', array('before' => 'auth', 'uses' => 'Sections\Front\Index@getModalConfirmDelete'));
Route::group(array('before' => 'auth'), function () {
    \Route::get('elfinder', 'Barryvdh\Elfinder\ElfinderController@showIndex');
    \Route::any('elfinder/connector',           'Barryvdh\Elfinder\ElfinderController@showConnector');
    \Route::get('elfinder/tinymce',             'Barryvdh\Elfinder\ElfinderController@showTinyMCE4');
    \Route::get("elfinder/navichat", function(){
        return \View::make("_default.navichatelfinder");
    });
});

Route::group(array('before' => 'auth'), function () {
    \Route::get('download/{file}', 'Sections\Front\Index@getDownload');
});

/** ------------------------------------------
 *  Admin Routes
 *  ------------------------------------------
 */
$user = \Auth::user();

if($user && $user->hasRole('admin'))
    include('AdminRouter.php');

/** ------------------------------------------
 *  Site Users
 *  ------------------------------------------
 */
if($user && $user->hasRole('local-manager'))
    include('NewLocalManagerRouter.php');

/** ------------------------------------------
 *  Hq Managers
 *  ------------------------------------------
 */
if($user && $user->hasRole('hq-manager'))
    include('HqManagerRouter.php');

if($user && $user->hasRole('accountant'))
    include('AccountantRouter.php');

if($user && $user->hasRole('client-relation-officer'))
    include('ClientRelationOfficers.php');


/** ------------------------------------------
 *  Area Managers
 *  ------------------------------------------
 */
if($user && $user->hasRole('area-manager'))
    include('AreaManagerRouter.php');


/** ------------------------------------------
 *  Visitors
 *  ------------------------------------------
 */
if($user && $user -> hasRole('visitor'))
    include('VisitorRouter.php');
    Route::group(array('prefix' => 'api'), function () {
        Route::group(array('prefix' => 'navitas-app'), function () {
            Route::post('create', array('uses' => 'ApiNavitasProbesController@postCreate'));
            Route::post('pin-confirm', array('uses' => 'ApiNavitasProbesController@postConfirmDevice'));
            Route::post('get-menu', array('before' => 'api_navitas_auth', 'uses' => 'ApiNavitasProbesController@getMenu'));
            Route::post('get-suppliers', array('before' => 'api_navitas_auth', 'uses' => 'ApiNavitasProbesController@getSuppliers'));
            Route::post('get-users', array('before' => 'api_navitas_auth', 'uses' => 'ApiNavitasProbesController@getStaff'));
            Route::post('get-services', array('before' => 'api_navitas_auth', 'uses' => 'ApiNavitasProbesController@getServices'));
            Route::post('get-unit', array('before' => 'api_navitas_auth', 'uses' => 'ApiNavitasProbesController@getUnit'));
            Route::post('calibration', array('before' => 'api_navitas_auth', 'uses' => 'ApiNavitasProbesController@postCalibration'));
            Route::post('service-temperature', array('before' => 'api_navitas_auth', 'uses' => 'ApiNavitasProbesController@postServiceTemperature'));
            Route::post('{supplier_id}/get-products', array('before' => 'api_navitas_auth', 'uses' => 'ApiNavitasProbesController@getSupplierProducts'));
            Route::post('goods-in', array('before' => 'api_navitas_auth', 'uses' => 'ApiNavitasProbesController@postMonitoredGoodsIn'));
        });
    });
    Route::group(array('prefix' => 'api/probe', 'namespace' => 'Api\Probes'), function () {
        $ctrl = 'ProbesController';
        $versions = [1, 2];
        foreach ($versions as $ver) {
            Route::group(array('prefix' => '/' . $ver, 'namespace' => 'V' . $ver), function () use ($ctrl, $ver) {
                Route::group(array('prefix' => 'auth', 'before' => "api_probes_auth:$ver"), function () use ($ctrl) {
                    Route::post('/get-supplier-products/{id}', $ctrl . '@postGetSupplierProducts');
                    Route::controller('/', $ctrl);
                });
                Route::post('create', array('uses' => $ctrl . '@postCreate'));
            });
        }
    });

/*/
$q='SELECT t.title, i.start, i.end
FROM new_cleaning_schedules_tasks2 AS t
INNER JOIN new_cleaning_schedules_items2 AS i ON i.task_id = t.id
WHERE t.unit_id = 15
AND DATE(i.start) = CURDATE() - 1';


$data = DB::select(DB::raw($q));
dd($data);

/*/
    Route::group(array('prefix' => 'api/pod', 'namespace' => 'Api\Pods','before' => "api_pods_check_hub_device"), function ()
    {
        $ctrl = 'PodsController';
        $versions = [1=>[1,2,3], 2];
        foreach ($versions as $key => $val) {
            $ver = is_array($val)?$key:$val;
            $namespace = 'V'.$ver.(($sv = \Mapic::getPodApiSubver($val)) ? ('\S'.$sv) : '');
            Route::group(['prefix' => '/' . $ver, 'namespace' => $namespace,'before' => "api_pods_auth:$namespace,$ver,$sv"], function () use ($ctrl) {
                Route::controller('/', $ctrl);
            });
        }
    });

    Route::group(['prefix' => 'api/droid','namespace' => 'Api\Droid',],function(){
        $versions = [1=>[0,1]];
        foreach ($versions as $version => $subversions) {
            $ctrl = 'DroidController';
            foreach ($subversions as $subversion) {
                $ver = $version.'.'.$subversion;
                Route::group(['prefix' => $ver,'namespace' => ('V'.$version.'\S'.$subversion)], function () use($ctrl,$ver)
                {
                    Route::get ('/timestamp',    $ctrl . '@getTimestamp');
                    Route::post('/auth',         $ctrl . '@postAuth');

                    Route::group(['before'=>'tokenCheck','after'=>'RefreshToken'], function () use($ctrl,$ver){
                        if(((float)$ver) >= 1.0)
                        {
                            Route::get ('/cleaning-schedules/tasks.json', $ctrl . '@getCleaningSchedulesTasks');
                            Route::get ('/cleaning-schedules/{id}/task.json', $ctrl . '@getCleaningSchedulesTask');
                            Route::get ('/cleaning-schedules/{id}/complete.json', $ctrl . '@getCleaningSchedulesCompleted');
                            Route::post('/cleaning-schedules/{id}/complete.json', $ctrl . '@postCleaningSchedulesComplete');
                            Route::get ('/cleaning-schedules/{id}/resolve.json', $ctrl . '@getCleaningSchedulesComplete');
                            Route::post('/cleaning-schedules/{id}/resolve.json', $ctrl . '@postCleaningSchedulesComplete');
                        }
                        Route::controller('/', $ctrl);
                    });
                });
            }
        }
    });


/*
Route::any('api/{api}/{ver}.{sub?}/', function ($target, $ver, $sub=null) {
        $namespace = '\\'.($target = ucfirst($target)).'\V'.$ver.($sub?'\S'.$sub:'');
        Route::group(['prefix' => '/' , 'namespace' => 'Api'.$namespace ], function ()use($target){
            $ctrl = $target.'Controller';
            Route::controller('/', $ctrl);
        });
    })->where('api','droid');
*/
    Route::get('/', array('before' => 'detectLang', 'uses' => 'Sections\Front\Index@getIndex'));