<?php
Route::group(array('prefix' => 'sys-files-uploader','namespace' => 'Modules', 'before' => 'auth'), function ()
{
    $ctrl = 'FilesUploader';
    Route::get ('/upload/{target_type}/{target_id}', $ctrl.'@getUpload');
    Route::post('/upload/{target_type}/{target_id}', $ctrl.'@postUpload');
    Route::get ('/file/download/{id}',               $ctrl.'@getFileDownload');
    Route::get ('/file/delete/{id}',                 $ctrl.'@getFileDelete');
});


Route::group(array('prefix' => 'profile','namespace' => 'Modules', 'before' => 'auth'), function ()
{
    $ctrl = 'Profile';
    Route::get ('/edit/password',                   $ctrl.'@getEditPassword');
    Route::get ('/edit/avatar',                     $ctrl.'@getEditAvatar');
    Route::get ('/edit/general',                    $ctrl.'@getEditGeneral');
    Route::post('/edit/password',                   $ctrl.'@postEditPassword');
    Route::post('/edit/avatar',                     $ctrl.'@postEditAvatar');
    Route::post('/edit/general',                    $ctrl.'@postEditGeneral');
});

Route::group(array('prefix' => 'unit','namespace' => 'Modules', 'before' => 'auth'), function ()
{
    $ctrl = 'Unit';
    Route::get ('/edit/rating-stars/{stars}',       $ctrl.'@getEditRatingStars');
    Route::post('/edit/rating-stars',               $ctrl.'@postEditRatingStars');
    Route::get ('/edit',                            $ctrl.'@getEdit');
    Route::get ('/edit/logo',                       $ctrl.'@getEditLogo');
    Route::post('/edit',                            $ctrl.'@postEdit');
    Route::post('/edit/logo',                       $ctrl.'@postEditLogo');
});

Route::group(array('prefix' => 'form-processor','namespace' => 'Modules', 'before' => 'auth'), function ()
{
    $ctrl = 'FormProcessor';
    Route::post('/data',                            $ctrl.'@postData');
    Route::get('/file/display/{id}',                $ctrl.'@getFileDisplay');
    Route::get('/file/download/{id}',               $ctrl.'@getFileDownload');
    Route::get('/file/delete/{id}',                 $ctrl.'@getFileDelete');
    Route::controller('/', $ctrl);
});
Entrust::routeNeedsRole('form-processor/*', array('local-manager'),Redirect::to('/'));

Route::group(array('prefix' => 'form-builder','namespace' => 'Modules', 'before' => 'auth'), function ()
{
    $ctrl = 'FormBuilder';
    Route::get ('/{id}/complete',              $ctrl.'@getComplete');
    Route::get ('/{id}/update',                $ctrl.'@getUpdate');
    Route::get ('/{id}/display',               $ctrl.'@getDisplay');
    Route::controller('/', $ctrl);
});
Entrust::routeNeedsRole('form-builder/*', array('local-manager'),Redirect::to('/'));

//-------
Route::group(array('prefix' => 'workflow','namespace' => 'Modules', 'before' => 'auth'), function ()
{
    $ctrl = 'Workflow';
    Route::get('/task/{id}/status/{status}',    ['as' => 'workflow.change-status','uses' => $ctrl.'@getChangeStatus']);
    Route::get('/data-details/task-{id}.json',  ['as' => 'workflow.task-details',  'uses' => $ctrl.'@getDetails']);
    Route::any('/data-sites.json',              ['as' => 'workflow.data-sites',    'uses' => $ctrl.'@dataSites']);
    Route::any('/data-officers.json',           ['as' => 'workflow.data-officers', 'uses' => $ctrl.'@dataOfficers']);
    Route::any('/datatable.json',               ['as' => 'workflow.datatable',     'uses' => $ctrl.'@datatable']);
    Route::get ('/{list?}',                     ['as' => 'workflow.list',          'uses'=>  $ctrl.'@getIndex'])->where('list','list');
    Route::controller('/',        $ctrl);
});
Entrust::routeNeedsRole('workflow*', array('client-relation-officer'),Redirect::to('/'));
//-------
Route::group(array('prefix' => 'scrum-board','namespace' => 'Modules', 'before' => 'auth'), function ()
{
    $ctrl = 'ScrumBoard';
    Route::get ('/{list?}',       ['as' => 'scrum-board.list',    'uses'=>  $ctrl.'@getIndex'])->where('list','list');
    Route::controller('/',        $ctrl);
});
Entrust::routeNeedsRole('scrum-board*', array('client-relation-officer'),Redirect::to('/'));
//-------
Route::group(array('prefix' => 'contacts','namespace' => 'Modules', 'before' => 'auth'), function ()
{
    $ctrl = 'Contacts';
    Route::get ('/{list?}',       ['as' => 'contacts.list',    'uses'=>  $ctrl.'@getIndex'])->where('list','list');
    //Route::controller('/',        $ctrl);
});
Entrust::routeNeedsRole('contacts*', array('client-relation-officer'),Redirect::to('/'));
//-------
Route::group(array('prefix' => 'calendar','namespace' => 'Modules', 'before' => 'auth'), function ()
{
    $ctrl = 'Calendar';
    Route::get ('/{list?}',       ['as' => 'calendar.list',    'uses'=>  $ctrl.'@getIndex'])->where('list','list');
    //Route::controller('/',        $ctrl);

});
Entrust::routeNeedsRole('calendar*', array('client-relation-officer'),Redirect::to('/'));
//-------

Route::group(array('prefix' => 'support-system','namespace' => 'Modules', 'before' => 'auth'), function ()
{
    $ctrl = 'SupportSystem';
    Route::controller('/',$ctrl);
    Route::get ('/',       ['as' => 'support_system.index', 'uses'=>  $ctrl.'@getIndex']);

});
Entrust::routeNeedsRole('support-system/categories*', array('admin'),Redirect::to('/support-system'));

/*
Route::group(array('prefix' => 'check-list','namespace' => 'Modules', 'before' => 'auth'), function ()
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
    Route::get ('/',                                ['as' => 'check_list.index', 'uses' => $ctrl.'@getIndex']);
});

Route::group(array('prefix' => 'compliance-diary','namespace' => 'Modules', 'before' => 'auth'), function ()
{
    $ctrl = 'ComplianceDiary';
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
    Route::get ('/',                                ['as' => 'compliance_diary.index', 'uses' => $ctrl.'@getIndex']);
});
*/


Route::group(array('prefix' => 'check-list','namespace' => 'Modules', 'before' => 'auth'), function () {
    $ctrl = 'CheckList';
    Route::get('/forms', ['as' => 'check_list.forms', 'uses' => $ctrl . '@getForms']);
    Route::get('/submitted', ['as' => 'check_list.submitted', 'uses' => $ctrl . '@getSubmittedList']);
    Route::get('/create', $ctrl . '@getCreate');
    Route::post('/create', $ctrl . '@postCreate');
    Route::get('/data', $ctrl . '@getData');
    Route::get('/datatable', $ctrl . '@getDatatable');
    Route::get('/submitted/{id}', $ctrl . '@getSubmittedDetails');
    Route::get('/details/{id}', $ctrl . '@getDetails');
    Route::get('/complete/{data}', $ctrl . '@getComplete');
    Route::post('/complete/{id}', $ctrl . '@postComplete');
    Route::get('/', ['as' => 'check_list.index', 'uses' => $ctrl . '@getIndex']);
});

Route::group(array('prefix' => 'compliance-diary','namespace' => 'Modules', 'before' => 'auth'), function () {
    $ctrl = 'ComplianceDiary';
    Route::get('/forms', ['as' => 'compliance_diary.forms', 'uses' => $ctrl . '@getForms']);
    Route::get('/submitted', ['as' => 'compliance_diary.submitted', 'uses' => $ctrl . '@getSubmittedList']);
    Route::get('/create', $ctrl . '@getCreate');
    Route::post('/create', $ctrl . '@postCreate');
    Route::get('/data', $ctrl . '@getData');
    Route::get('/datatable', $ctrl . '@getDatatable');
    Route::get('/submitted/{id}', $ctrl . '@getSubmittedDetails');
    Route::get('/details/{id}', $ctrl . '@getDetails');
    Route::get('/complete/{data}', $ctrl . '@getComplete');
    Route::post('/complete/{id}', $ctrl . '@postComplete');
    Route::get('/', ['as' => 'compliance_diary.index', 'uses' => $ctrl . '@getIndex']);
});

Route::group(array('prefix' => 'cleaning-schedule','namespace' => 'Modules', 'before' => 'auth'), function ()
{
    $ctrl = 'CleaningSchedule';
    Route::get ('/forms',                           ['as' => 'cleaning_schedules.forms', 'uses' => $ctrl.'@getForms']);
    Route::get ('/submitted',                       ['as' => 'cleaning_schedules.submitted', 'uses' => $ctrl.'@getSubmittedList']);
    Route::get ('/create',                          $ctrl.'@getCreate');
    Route::post('/create',                          $ctrl.'@postCreate');
    Route::get ('/data',                            $ctrl.'@getData');
    Route::get ('/datatable',                       $ctrl.'@getDatatable');
    Route::get ('/submitted/{id}',                  $ctrl.'@getSubmittedDetails');
    Route::get ('/details/{id}',                    $ctrl.'@getDetails');
    Route::get ('/complete/{data}',                 $ctrl.'@getComplete');
    Route::post('/complete/{id}',                   $ctrl.'@postComplete');
    Route::get ('/',                                ['as' => 'cleaning_schedules.index', 'uses' => $ctrl.'@getIndex']);
    /*
    Route::get ('/tasks-list',                      ['as' => 'new_cleaning_schedules.tasks', 'uses' => $ctrl.'@getTasksList']);
    Route::get ('/tasks-list-datatable',            $ctrl.'@getTasksListDatatable');
    Route::get ('/edit/{id}',                       $ctrl.'@getEdit');
    Route::post('/edit/{id}',                       $ctrl.'@postEdit');
    Route::post('/edit/resize',                     $ctrl.'@postResize');
    Route::post('/edit/drop',                       $ctrl.'@postDrop');
    Route::get ('/delete/{id}',                     $ctrl.'@getDelete');
    Route::get ('/delete-by-staff',                 $ctrl.'@getDeleteByStaff');
    Route::post('/delete-by-staff',                 $ctrl.'@postDeleteByStaff');
    Route::get ('/submitted/{id}/{destin}-delete',  $ctrl.'@getSubmittedDelete');
    */

});

Entrust::routeNeedsRole('cleaning-schedule*', array('local-manager'),Redirect::to('/'));

Route::group(array('prefix' => 'notifications','namespace' => 'Modules', 'before' => 'auth'), function ()
{
    $ctrl = 'Notifications';
    Route::get ('/{id}/remove',                     $ctrl.'@getRemove');
    Route::get ('/accept/{id}',                     $ctrl.'@getAccept');
    Route::get ('/datatable',                       $ctrl.'@getDatatable');
    Route::get ('/',                                $ctrl.'@getIndex');
});

Route::group(array('prefix' => 'chat','namespace' => 'Modules', 'before' => 'auth'), function ()
{
    $ctrl = 'Chat';
    Route::post('message',                          $ctrl.'@postMessage');
    Route::get ('/{threadIdx?}',                    $ctrl.'@getIndex');
});