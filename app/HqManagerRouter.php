<?php
Route::group(array('namespace' => 'Sections\HqManagers', 'before' => 'auth|detectLang|detectTimezone'), function()
{

    Route::group(array('prefix' => 'invalid-temperatures'), function()
    {
        $ctrl = 'InvalidTemperatures';

        Route::get ('/datatable',                    $ctrl.'@getDatatable');
        Route::get ('/',                             $ctrl.'@getIndex');

    });

    Route::group(array('prefix' => 'online-users'), function()
    {
        $ctrl = 'OnlineUsers';
        Route::get ('/datatable',                    $ctrl.'@getDatatable');
        Route::get ('/',                             $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'users'), function()
    {
        $ctrl = 'Users';
        Route::get ('/datatable',                    $ctrl.'@getDatatable');
        Route::get ('/edit/password/{id}',           $ctrl.'@getEditPassword');
        Route::post('/edit/password/{id}',           $ctrl.'@postEditPassword');
        Route::get ('/edit/avatar/{id}',             $ctrl.'@getEditAvatar');
        Route::post('/edit/avatar/{id}',             $ctrl.'@postEditAvatar');
        Route::get ('/edit/{id}',                    $ctrl.'@getEdit');
        Route::post('/edit/{id}',                    $ctrl.'@postEdit');
        Route::get ('/create/',                      $ctrl.'@getCreate');
        Route::post('/create/',                      $ctrl.'@postCreate');
        Route::get ('/create/form-fields/{id}',      $ctrl.'@getCreateFormFields');
        Route::get ('/edit/{uid}/form-fields/{rid}', $ctrl.'@getEditFormFields');
        Route::get ('/active/{id}',                  $ctrl.'@getActive');
        Route::get ('/confirmed/{id}',               $ctrl.'@getConfirmed');
        Route::get ('/send-confirm-url/{id}',        $ctrl.'@getSendConfirmEmail');
        Route::get ('/send-access-url/{id}',         $ctrl.'@getSendAccessEmail');
        Route::get ('/delete/{id}',                  $ctrl.'@getDelete');
        Route::get ('/',                             $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'index'), function()
    {
        $ctrl = 'Index';
        Route::get ('/{box}-from/{date_from}',          $ctrl.'@getSwitchDateFrom') -> where('box','summary|loggons') -> where ('date_from','today|last-week|last-month');
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'profile'), function()
    {
        $ctrl = 'Profile';
        Route::get ('/edit/password',                   $ctrl.'@getEditPassword');
        Route::get ('/edit/avatar',                     $ctrl.'@getEditAvatar');
        Route::get ('/edit/general',                    $ctrl.'@getEditGeneral');
        Route::post('/edit/password',                   $ctrl.'@postEditPassword');
        Route::post('/edit/avatar',                     $ctrl.'@postEditAvatar');
        Route::post('/edit/general',                    $ctrl.'@postEditGeneral');
    });


    Route::group(array('prefix' => 'headquarter'), function()
    {
        $ctrl = 'Headquarter';
        Route::get ('edit/',                            $ctrl.'@getEdit');
        Route::post('edit/',                            $ctrl.'@postEdit');
        Route::get ('/edit/logo',                       $ctrl.'@getEditLogo');
        Route::post('/edit/logo',                       $ctrl.'@postEditLogo');
    });


    Route::group(array('prefix' => 'units'), function()
    {
        $ctrl = 'Units';
        Route::get ('/edit/logo/{id}',               $ctrl.'@getEditLogo');
        Route::post('/edit/logo/{id}',               $ctrl.'@postEditLogo');

        Route::get ('/edit/{id}',                    $ctrl.'@getEdit');
        Route::post('/edit/{id}',                    $ctrl.'@postEdit');

        Route::get ('/create/',                      $ctrl.'@getCreate');
        Route::post('/create/',                      $ctrl.'@postCreate');

        Route::get ('/delete/{id}',                  $ctrl.'@getDelete');
        Route::get ('/active/{id}',                  $ctrl.'@getActive');
        Route::get ('datatable',                     $ctrl.'@getDatatable'); //id = hq units
        Route::get ('/set-current/{id?}',            $ctrl.'@getSetUnitId');

        Route::get ('/users',                       'Users@getIndex');
        Route::get ('/',                             $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'haccp-company'), function() //hq
    {
        $ctrl = 'HaccpCompany';
        Route::get ('/create',                          $ctrl.'@getCreate');
        Route::post('/create',                          $ctrl.'@postCreate');
        Route::get ('/edit/{id}',                       $ctrl.'@getEdit');
        Route::post('/edit/{id}',                       $ctrl.'@postEdit');
        Route::get ('/active/{id}',                     $ctrl.'@getActive');
        Route::post('/edit/update',                     $ctrl.'@postUpdateOrder');
        Route::get ('/delete/{id}',                     $ctrl.'@getDelete');
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'knowledge-company'), function()
    {
        $ctrl = 'KnowledgeCompany';
        Route::get ('/create',                          $ctrl.'@getCreate');
        Route::post('/create',                          $ctrl.'@postCreate');
        Route::get ('/edit/{knowledge}',                $ctrl.'@getEdit');
        Route::post('/edit/{knowledge}',                $ctrl.'@postEdit');
        Route::get ('/active/{knowledge}',              $ctrl.'@getActive');
        Route::post('/edit/update',                     $ctrl.'@postUpdateOrder');
        Route::get ('/delete/{knowledge}',              $ctrl.'@getDelete');
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::controller('/units-map',         'UnitsMap');
    Route::controller('/area-manager-stats','AreaManagerStats');
    Route::controller('/usage-stats',       'UsageStats');
    Route::controller('/use-navitas',       'UseNavitas');
    Route::controller('/',                  'Index');
});
