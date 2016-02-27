<?php
Route::group(array('namespace' => 'Sections\LocalManagers', 'before' => 'auth|detectLang|detectTimezone'), function()
{

    Route::group(array('prefix' => 'sys-files-uploader'), function()
    {
        $ctrl = 'FilesUploader';
        Route::get ('/upload/{target_type}/{target_id}', $ctrl.'@getUpload');
        Route::post('/upload/{target_type}/{target_id}', $ctrl.'@postUpload');
        Route::get ('/file/download/{id}',               $ctrl.'@getFileDownload');
        Route::get ('/file/delete/{id}',                 $ctrl.'@getFileDelete');
    });

    Route::group(array('prefix' => 'check-list'), function()
    {
        $ctrl = 'CheckList';
        Route::get ('/tasks-list',                      $ctrl.'@getTasksList');
        Route::get ('/tasks-list-datatable',            $ctrl.'@getTasksListDatatable');
        Route::get ('/create',                          $ctrl.'@getCreate');
        Route::post('/create',                          $ctrl.'@postCreate');
        Route::get ('/complete/{data}',                 $ctrl.'@getComplete');
        Route::post('/complete/{id}',                   $ctrl.'@postComplete');
        Route::get ('/forms',                           $ctrl.'@getForms');
        Route::get ('/edit/{id}',                       $ctrl.'@getEdit');
        Route::post('/edit/{id}',                       $ctrl.'@postEdit');
        Route::post('/edit/resize',                     $ctrl.'@postResize');
        Route::post('/edit/drop',                       $ctrl.'@postDrop');
        Route::get ('/delete/{id}',                     $ctrl.'@getDelete');
        Route::get ('/delete-by-staff',                 $ctrl.'@getDeleteByStaff');
        Route::post('/delete-by-staff',                 $ctrl.'@postDeleteByStaff');
        Route::get ('/data',                            $ctrl.'@getData');
        Route::get ('/datatable',                       $ctrl.'@getDatatable');
        Route::get ('/submitted/{id}/{destin}-details', $ctrl.'@getSubmittedDetails');
        Route::get ('/submitted/{id}/{destin}-delete',  $ctrl.'@getSubmittedDelete');
        Route::get ('/submitted',                       $ctrl.'@getSubmittedList');
        Route::get ('/',                                ['as' => 'check-list.index', 'uses' => $ctrl.'@getIndex']);
    });

    Route::group(array('prefix' => 'new-compliance-diary'), function()
    {
        $ctrl = 'NewComplianceDiary';
        Route::get ('/tasks-list',                      $ctrl.'@getTasksList');
        Route::get ('/tasks-list-datatable',            $ctrl.'@getTasksListDatatable');

        Route::get ('/select-create',                   $ctrl.'@getSelectCreate');
        Route::get ('/create',                          $ctrl.'@getCreate');
        Route::post('/create',                          $ctrl.'@postCreate');

        Route::get ('/edit/{id}',                       $ctrl.'@getEdit');
        Route::post('/edit/{id}',                       $ctrl.'@postEdit');
        Route::post('/edit/resize',                     $ctrl.'@postResize');
        Route::post('/edit/drop',                       $ctrl.'@postDrop');
        Route::get ('/delete/{id}',                     $ctrl.'@getDelete');
        Route::get ('/data',                            $ctrl.'@getData');
        Route::get ('/datatable',                       $ctrl.'@getDatatable');
        Route::get ('/',                                ['as' => 'compliance-diary.index', 'uses' => $ctrl.'@getIndex']);
    });

    Route::group(array('prefix' => 'health-questionnaires'), function()
    {
        $ctrl = 'HealthQuestionnaires';
        Route::get ('/forms-list',                      $ctrl.'@getFormsList');
        Route::get ('/submitted/{id}/details',          $ctrl.'@getSubmittedDetails');
        Route::get ('/submitted',                       $ctrl.'@getSubmittedList');

        Route::get ('/staff/{id}/datatable',            $ctrl.'@getStaffDatatable');
        Route::get ('/staff/{id}/list',                 $ctrl.'@getStaffList');

        Route::get ('/datatable',                       $ctrl.'@getDatatable');
        Route::get ('/delete/{id}',                     $ctrl.'@getDelete');
        Route::get ('/',                                ['as' => 'health-questionnaires.index', 'uses' => $ctrl.'@getIndex']);
    });


    Route::group(array('prefix' => 'messages'), function()
    {
        $ctrl = 'Messages';
        Route::get('/thread/{thread}',  $ctrl.'@getThread');
        Route::post('/thread/{thread}', $ctrl.'@postThread');
        Route::controller('/',          $ctrl);
    });

    Route::group(array('prefix' => 'pods'), function()
    {
        Route::group(array('prefix' => 'areas'), function()
        {
            $ctrl = 'PodsAreas';
            Route::get ('/refresh',          $ctrl.'@getRefresh');
            Route::post('/update',           $ctrl.'@postUpdateOrder');
            Route::get ('/create/{type?}',   $ctrl.'@getCreate');
            Route::post('/create/{type?}',   $ctrl.'@postCreate');
            Route::get ('/edit/{podsArea}',  $ctrl.'@getEdit');
            Route::post('/edit/{podsArea}',  $ctrl.'@postEdit');
            Route::get ('/delete/{podsArea}',$ctrl.'@getDelete');
            Route::controller('/',           $ctrl);
        });

        Route::group(array('prefix' => 'sensors'), function()
        {
            $ctrl = 'PodsSensors';
            Route::get ('/delete/{id}',      $ctrl.'@getDelete');
            Route::get ('/edit/{id}',        $ctrl.'@getEdit');
            Route::get ('/load-areas/{id?}', $ctrl.'@getLoadAreas');
            Route::controller('/',           $ctrl);
        });

    });
    Route::group(array('prefix' => 'probes'), function() {
        Route::group(array('prefix' => 'areas'), function () {
            $ctrl = 'ProbesAreas';
            Route::get ('/create',           $ctrl . '@getCreate');
            Route::post('/create',           $ctrl . '@postCreate');
            Route::get ('/edit/{id}',        $ctrl . '@getEditArea');
            Route::post('/edit/{id}',        $ctrl . '@postEditArea');
            Route::get ('/delete/{id}',      $ctrl . '@getDelete');
            Route::get ('/list',             $ctrl . '@getAreasList');
            Route::get ('/',                 $ctrl . '@getIndex');
        });

        Route::group(array('prefix' => 'menu-items'), function()
        {
            $ctrl = 'ProbesMenuItems';
            Route::post('/edit/update', $ctrl.'@postUpdate');
            Route::get ('/',            $ctrl.'@getIndex');
        });

        Route::group(array('prefix' => 'devices'), function()
        {
            $ctrl = 'ProbesDevices';
            Route::get ('/edit/activate/{id}',              $ctrl.'@getActivate');
            Route::get ('/edit/{id}',                       $ctrl.'@getEdit');
            Route::post('/edit/{id}',                       $ctrl.'@postEdit');
            Route::get ('/delete/{id}',                     $ctrl.'@getDeleteDevice');
            Route::get ('/',                                $ctrl.'@getIndex');
        });
    });

    Route::group(array('prefix' => 'suppliers'), function()
    {
        $ctrl = 'Suppliers';
        Route::get ('/datatable',                       $ctrl.'@getDatatable');

        Route::get ('/edit/logo/{id}',                  $ctrl.'@getEditLogo');
        Route::post('/edit/logo/{id}',                  $ctrl.'@postEditLogo');
        Route::get ('/edit/general/{id}',               $ctrl.'@getEditGeneral');
        Route::post('/edit/general/{id}',               $ctrl.'@postEditGeneral');
        Route::get ('/details/{id}',                    $ctrl.'@getDetails');
        Route::get ('/notes/{id}',                      $ctrl.'@getNotes');
        Route::get ('/incidents/{id}',                  $ctrl.'@getIncidents');
        Route::get ('/details/{id}',                    $ctrl.'@getDetails');
        Route::get ('/products/autocomplete/{tag}',     $ctrl.'@getProductsAutocomplete');
        Route::get ('/create',                          $ctrl.'@getCreate');
        Route::post('/create',                          $ctrl.'@postCreate');
        Route::get ('/import',                          $ctrl.'@getImport');
        Route::post('/import',                          $ctrl.'@postImport');
        Route::get ('/delete/{id}',                     $ctrl.'@getDelete');
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'goods-in'), function()
    {
        $ctrl = 'GoodsIn';
        Route::get ('/datatable/supplier/{id}',         $ctrl.'@getDatatable');
        Route::get ('/datatable',                       $ctrl.'@getDatatable');

        Route::get ('/details/{id}',                    $ctrl.'@getDetails');

        Route::get ('/edit/{id}',                       $ctrl.'@getEdit');
        Route::post('/edit/{id}',                       $ctrl.'@postEdit');

        Route::get ('/compliant/{id}',                  $ctrl.'@getCompliant');

        Route::get ('/create',                          $ctrl.'@getCreate');
        Route::post('/create',                          $ctrl.'@postCreate');

        Route::controller('/', $ctrl);
    });

    Route::group(array('prefix' => 'staff'), function()
    {
        $ctrl = 'Staff';
        Route::get ('/create',                          $ctrl.'@getCreate');
        Route::post('/create',                          $ctrl.'@postCreate');

        Route::get ('/import',                          $ctrl.'@getImport');
        Route::post('/import',                          $ctrl.'@postImport');

        Route::get ('/edit/avatar/{id}',                $ctrl.'@getEditAvatar');
        Route::post('/edit/avatar/{id}',                $ctrl.'@postEditAvatar');

        Route::get ('/edit/general/{id}',               $ctrl.'@getEditGeneral');
        Route::post('/edit/general/{id}',               $ctrl.'@postEditGeneral');

        Route::get ('/health/{id}',                     $ctrl.'@getHealth');
        Route::get ('/trainings/{id}',                  $ctrl.'@getTrainings');

        Route::get ('/delete/{id}',                     $ctrl.'@getDelete');
        Route::get ('/datatable',                       $ctrl.'@getDatatable');
        Route::get ('/settings',                        $ctrl.'@getIndex');
        Route::get ('/{id?}',                           $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'trainings'), function()
    {
        $ctrl = 'Trainings';
        Route::controller('/', $ctrl);
    });


    Route::group(array('prefix' => 'reports'), function()
    {
        $ctrl = 'Reports';
        Route::post ('/',                               $ctrl.'@postCreate');
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'temperatures-alert-box'), function()
    {
        $ctrl = 'TemperaturesAlertBox';

        Route::get ('/create/folder',                   $ctrl.'@getCreateFolder');
        Route::post('/create/folder',                   $ctrl.'@postCreateFolder');
        Route::get ('/delete/folder/{id}',              $ctrl.'@getDeleteFolder');

        Route::get ('/create/area/{id}',                   $ctrl.'@getCreateArea');
        Route::post('/create/area/{id}',                   $ctrl.'@postCreateArea');
        Route::get ('/delete/area/{id}',                $ctrl.'@getDeleteArea');

        Route::get ('/edit/{id}',                       $ctrl.'@getEdit');
        Route::post('/edit/{id}',                       $ctrl.'@postEdit');

        Route::get ('/folder/{id}',                     $ctrl.'@getAreas');

        Route::get ('/load-areas/{group}',                 $ctrl.'@getLoadAreas');
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'food-incidents'), function()
    {
        $ctrl = 'FoodIncidents';
        Route::get ('/details/{id}',                    $ctrl.'@getDetails');
        Route::get ('/delete/{id}',                     $ctrl.'@getDelete');
        Route::get ('/edit/{id}',                       $ctrl.'@getEdit');
        Route::post('/edit/{id}',                       $ctrl.'@postEdit');
        Route::get ('/datatable',                       $ctrl.'@getDatatable');
        Route::post('/create',                          $ctrl.'@postCreate');
        Route::get ('/create',                          $ctrl.'@getCreate');
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'searching'), function()
    {
        $ctrl = 'Searching';
        Route::post ('/',                          $ctrl.'@postFind');
    });

    Route::group(array('prefix' => 'outstanding-tasks'), function()
    {
        $ctrl = 'OutstandingTasks';
        Route::get ('/load-inputs/{section}',           $ctrl.'@getLoadInputs');
        Route::get ('/datatable',                       $ctrl.'@getDashboardDatatable');
        Route::get ('/resolve-all',                     $ctrl.'@getResolveAll');
        Route::post('/resolve-all',                     $ctrl.'@postResolveAll');
        Route::get ('/resolve/{id}',                    $ctrl.'@getResolve');
        Route::post('/resolve/{id}',                    $ctrl.'@postResolve');
    });


    Route::group(array('prefix' => 'site-haccp'), function()
    {
        $ctrl = 'HaccpSite';
        Route::get ('/create',                          $ctrl.'@getCreate');
        Route::post('/create',                          $ctrl.'@postCreate');
        Route::get ('/edit/{haccp}',                    $ctrl.'@getEdit');
        Route::post('/edit/{haccp}',                    $ctrl.'@postEdit');
        Route::get ('/active/{haccp}',                  $ctrl.'@getActive');
        Route::post('/edit/update',                     $ctrl.'@postUpdateOrder');
        Route::get ('/delete/{haccp}',                  $ctrl.'@getDelete');
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'haccp'), function()
    {
        $ctrl = 'Haccp';
        Route::group(array('prefix' => 'storage'), function() use($ctrl) {
            Route::get('/item/{haccp}',           $ctrl . '@getStorageItem');
            Route::get('/',                       $ctrl . '@getStorageIndex');
        });
        Route::group(array('prefix' => 'forms'), function() use($ctrl) {
            Route::get('/submitted',              $ctrl . '@getFormsSubmittedList');
            Route::get('/submitted/{id}/details', $ctrl . '@getFormsSubmittedDetails');
            Route::get('/datatable',              $ctrl . '@getFormsDatatable');
            Route::get('/',                       $ctrl . '@getFormsIndex');
        });
        Route::get ('/',                          $ctrl . '@getStorageIndex');
    });

    Route::group(array('prefix' => 'knowledge'), function()
    {
        $ctrl = 'Knowledge';
        Route::group(array('prefix' => 'storage'), function() use($ctrl)
        {
            Route::get ('/item/{knowledge}',      $ctrl . '@getStorageItem');
            Route::get ('/pdf/{knowledge}',       $ctrl . '@getStoragePdf');
            Route::get('/',                       $ctrl . '@getStorageIndex');
        });
        Route::group(array('prefix' => 'forms'), function() use($ctrl) {
            Route::get('/submitted',              $ctrl . '@getFormsSubmittedList');
            Route::get('/submitted/{id}/details', $ctrl . '@getFormsSubmittedDetails');
            Route::get('/datatable',              $ctrl . '@getFormsDatatable');
            Route::get('/',                       $ctrl . '@getFormsIndex');
        });
        Route::get ('/',                          $ctrl . '@getStorageIndex');
    });

    Route::group(array('prefix' => 'site-knowledge'), function()
    {
        $ctrl = 'KnowledgeSite';
        Route::get ('/create',                          $ctrl.'@getCreate');
        Route::post('/create',                          $ctrl.'@postCreate');
        Route::get ('/edit/{knowledge}',                $ctrl.'@getEdit');
        Route::post('/edit/{knowledge}',                $ctrl.'@postEdit');
        Route::get ('/active/{knowledge}',              $ctrl.'@getActive');
        Route::post('/edit/update',                     $ctrl.'@postUpdateOrder');
        Route::get ('/delete/{knowledge}',              $ctrl.'@getDelete');
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

    Route::group(array('prefix' => 'unit'), function()
    {
        $ctrl = 'Unit';
        Route::get ('/edit/rating-stars/{stars}',       $ctrl.'@getEditRatingStars');
        Route::post('/edit/rating-stars',               $ctrl.'@postEditRatingStars');
        Route::get ('/edit',                            $ctrl.'@getEdit');
        Route::get ('/edit/logo',                       $ctrl.'@getEditLogo');
        Route::post('/edit',                            $ctrl.'@postEdit');
        Route::post('/edit/logo',                       $ctrl.'@postEditLogo');
    });





    Route::group(array('prefix' => 'photos'), function() //prototyp
    {
        $ctrl = 'Photos';
        Route::post('/create/{target}/{id}',            $ctrl.'@getCreate');
        Route::get ('/delete/{id}',                     $ctrl.'@getDelete');
        Route::get ('/photos/{target}/{id}',            $ctrl.'@getPhotos');
    });

    Route::group(array('prefix' => 'signatures'), function()
    {
        $ctrl = 'Signatures';
        Route::get ('/edit',                            $ctrl.'@getCreate');
        Route::controller('/',                          $ctrl);
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'temperatures'), function()
    {
        $ctrl = 'Temperatures';
        Route::group(array('prefix' => 'forms'), function() use($ctrl) {
            Route::get('/submitted',              $ctrl . '@getFormsSubmittedList');
            Route::get('/submitted/{id}/details', $ctrl . '@getFormsSubmittedDetails');
            Route::get('/datatable',              $ctrl . '@getFormsDatatable');
            Route::get('/',                       $ctrl . '@getFormsIndex');
        });
        Route::get ('/create',                          $ctrl.'@getCreate') ;
        Route::post('/create',                          $ctrl.'@postCreate') ;
        Route::get ('/{group}/{id}/{date?}',            $ctrl.'@getAreaTemperatures') ;
        Route::get ('/datatable/{group}/{id}/{date?}',  $ctrl.'@getDatatableArea');
        Route::get ('/datatable/{group}/{date?}',       $ctrl.'@getDatatableAreas');
        Route::get ('/{group}/{date?}',                 $ctrl.'@getAreasTemperatures') ;


        Route::get ('/',                                $ctrl.'@getIndex');


//area

//group

        //Route::get ('/details/{group}/{date?}',         $ctrl.'@getDeviceDetails') -> where('date', 'last-100|today|this-week|this-month|last-month|this-year') -> where('group', 'freezers|fridges|probes');
        //Route::get ('/datatable',                       $ctrl.'@getTemperatures');


    });

    Route::group(array('prefix' => 'navinotes'), function()
    {
        $ctrl = 'Navinotes';
        Route::post('/create/files/{id?}',              $ctrl.'@postCreateFiles');
        Route::post('/create',                          $ctrl.'@postCreate');
        Route::get ('/datatable',                       $ctrl.'@getDatatable');
        Route::get ('/create',                          $ctrl.'@getCreate');
        Route::get ('/delete/file/{id}',                $ctrl.'@getDeleteFile');
        Route::get ('/delete/{id}',                     $ctrl.'@getDelete');
        Route::get ('/files/{id?}',                     $ctrl.'@getFiles');
        Route::get ('/details/{id}',                    $ctrl.'@getDetails');
        Route::get ('/edit/{id}',                       $ctrl.'@getEdit');
        Route::get ('/',                                $ctrl.'@getIndex');
    });
    Route::controller('/', 'Index');
});