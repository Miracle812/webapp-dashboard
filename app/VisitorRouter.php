<?php
Route::group(array('namespace' => 'Sections\Visitors', 'before' => 'auth|detectLang|detectTimezone'), function()
{
    Route::controller('/food-incidents',            'FoodIncidents');
    Route::controller('/outstanding-tasks',         'OutstandingTasks');
    Route::controller('/goods-in',                  'GoodsIn');
    Route::controller('/reports',                   'Reports');
    Route::controller('/suppliers',                 'Suppliers');
    Route::controller('/staff',                     'Staff');
    Route::controller('/trainings',                 'Trainings');
    Route::controller('/health-questionnaires',     'HealthQuestionnaires');
    Route::controller('/cleaning-schedule',         'NewCleaningSchedule');
    Route::controller('/compliance-diary',          'complianceDiary');
    Route::controller('/check-list-daily',          'CheckListDaily');
    Route::controller('/check-list-monthly',        'CheckListMonthly');

    Route::group(array('prefix' => 'temperatures'), function() {
        $ctrl = 'Temperatures';
        Route::get('/{group}/{id}/{date?}',             $ctrl . '@getAreaTemperatures');
        Route::get('/datatable/{group}/{id}/{date?}',   $ctrl . '@getDatatableArea');
        Route::get('/datatable/{group}/{date?}',        $ctrl . '@getDatatableAreas');
        Route::get('/{group}/{date?}',                  $ctrl . '@getAreasTemperatures');
        Route::get('/',                                 $ctrl . '@getIndex');
    });

    Route::group(array('prefix' => 'knowledge'), function()
    {
        $ctrl = 'Knowledge';
        Route::get ('/item/{knowledge}',                $ctrl.'@getItem');
        Route::get ('/pdf/{knowledge}',                 $ctrl.'@getPdf');
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'haccp'), function()
    {
        $ctrl = 'Haccp';
        Route::get ('/item/{haccp}',                    $ctrl.'@getItem');
        Route::get ('/',                                $ctrl.'@getIndex');

    });

    Route::group(array('prefix' => 'navinotes'), function()
    {
        $ctrl = 'Navinotes';
        Route::get ('/datatable',                       $ctrl.'@getDatatable');
        Route::get ('/files/{id?}',                     $ctrl.'@getFiles');
        Route::get ('/details/{id}',                    $ctrl.'@getDetails');
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'sys-files-uploader'), function()
    {
        $ctrl = 'FilesUploader';
        Route::get ('/upload/{target_type}/{target_id}', $ctrl.'@getUpload');
        Route::get ('/file/download/{id}',               $ctrl.'@getFileDownload');
    });

    Route::controller('/',                          'Index');
});