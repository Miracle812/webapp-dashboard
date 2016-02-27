<?php

Route::group(array('prefix' => 'billing','namespace' => 'Modules', 'before' => 'auth|detectLang|detectTimezone'), function ()
{
    $ctrl = 'Billing';
    Route::controller('/',$ctrl);
});
Entrust::routeNeedsRole('billing*', array('admin'),Redirect::to('/'));


Route::pattern('hardware',     'all|probes|pods|hubs|tablets');
Route::group(array('namespace' => 'Sections\Admins', 'before' => 'auth|detectLang|detectTimezone'), function()
{
    Route::group(array('prefix' => 'non-compliant-trends'), function()
    {
        $ctrl = 'NonCompliantTrends';
        Route::get ('delete/{id}',  $ctrl.'@getDelete');
        Route::get ('create',       $ctrl.'@getCreate');
        Route::get ('answers',      $ctrl.'@getAnswers');
        Route::get ('datatable',    $ctrl.'@getDatatable');
        Route::post('update',       $ctrl.'@postUpdate');
        Route::get ('/',            $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'hardware'), function()
    {
        $ctrl = 'Hardware';
        Route::get ('/datatable/{hardware}',$ctrl.'@getDatatable');
        Route::get ('/{hardware}/',$ctrl.'@getHardwareList');
        Route::get ('/{hardware}/{id}/edit',$ctrl.'@getEdit');
        Route::get ('/{hardware}/{id}/delete',$ctrl.'@getDelete');
        Route::get ('/{hardware}/create',$ctrl.'@getCreate');
        Route::get ('/sites/{id}',$ctrl.'@getSites');
        Route::post('/set-client',$ctrl.'@postClient');
        Route::get ('/',$ctrl.'@getList');
        Route::controller('/', $ctrl);
    });


    Route::group(array('prefix' => 'users'), function()
    {
        $ctrl = 'Users';
        Route::get ('/edit/password/{id}',           $ctrl.'@getEditPassword');
        Route::post('/edit/password/{id}',           $ctrl.'@postEditPassword');
        Route::get ('/edit/avatar/{id}',             $ctrl.'@getEditAvatar');
        Route::post('/edit/avatar/{id}',             $ctrl.'@postEditAvatar');
        Route::get ('/edit/{id}',                    $ctrl.'@getEdit');
        Route::post('/edit/{id}',                    $ctrl.'@postEdit');

        Route::get ('/create/form-fields/{id}',      $ctrl.'@getCreateFormFields');
        Route::get ('/edit/{uid}/form-fields/{rid}', $ctrl.'@getEditFormFields');
        Route::get ('/active/{id}',                  $ctrl.'@getActive');
        Route::get ('/confirmed/{id}',               $ctrl.'@getConfirmed');
        Route::get ('/send-confirm-url/{id}',        $ctrl.'@getSendConfirmEmail');
        Route::get ('/send-access-url/{id}',         $ctrl.'@getSendAccessEmail');
        Route::get ('/delete/{id}',                  $ctrl.'@getDelete');

        Route::controller('/', $ctrl);

    });

    Route::group(array('prefix' => 'auto-messages'), function()
    {
        $ctrl = 'AutoMessages';
        Route::get ('/delete/group/{id}',                 $ctrl.'@getDeleteGroup');
        Route::get ('/active/group/{id}',                 $ctrl.'@getActiveGroup');
        Route::get ('/groups/datatable',                  $ctrl.'@getGroupsDatatable');
        Route::get ('/groups/create',                     $ctrl.'@getGroupsCreate');
        Route::post('/groups/create',                     $ctrl.'@postGroupsCreate');

        Route::get ('/group/{id}/edit',                   $ctrl.'@getGroupEdit');
        Route::post('/group/{id}/edit',                   $ctrl.'@postGroupEdit');

        Route::get ('/group/{id}/messages',               $ctrl.'@getMsgList');
        Route::get ('/group/{id}/msg/create',             $ctrl.'@getMsgCreate');
        Route::post('/group/{id}/msg/create',             $ctrl.'@postMsgCreate');
        Route::post('/group/{id}/sort/update',            $ctrl.'@postSortUpdate');

        Route::get ('/msg/{id}/edit',                     $ctrl.'@getMsgEdit');
        Route::post('/msg/{id}/edit',                     $ctrl.'@postMsgEdit');

        Route::get ('/delete/msg/{id}',                   $ctrl.'@getMsgDelete');
        Route::get ('/delete/group/{id}',                 $ctrl.'@getGroupDelete');

        Route::controller('/', $ctrl);
    });

    Route::group(array('prefix' => 'forms-manager'), function()
    {
        $ctrl = 'FormsManager';
        Route::get ('/form/{id}/copy',                  $ctrl.'@getCopyForm');
        Route::get ('/form/{id}/assigned',              $ctrl.'@getAssigned');
        Route::post('/form/{id}/assigned',              $ctrl.'@postAssigned');
        Route::get ('/form/{id}/create',                $ctrl.'@getCreate');
        Route::get ('/form/{id}/display',               $ctrl.'@getDisplay');
        Route::get ('/form/{id}/add/{type}',            $ctrl.'@getAddItem');
        Route::post('/form/{id}/add/{type}',            $ctrl.'@postAddItem');
        Route::get ('/form/{id}/refresh-items',         $ctrl.'@getRefreshItems');
        Route::get ('/load-groups/{type}/{id?}',        $ctrl.'@getLoadGroups');
        Route::get ('/copy/item/{id}',                  $ctrl.'@getCopyItem');
        Route::get ('/edit/item/{id}',                  $ctrl.'@getEditItem');
        Route::post('/edit/item/{id}',                  $ctrl.'@postEditItem');
        Route::post('/edit/form/{id}',                  $ctrl.'@postEditForm');
        Route::get ('/editable/{type}',                 $ctrl.'@postEditable');
        Route::get ('/delete/item/{id}',                $ctrl.'@postDeleteItem');
        Route::get ('/delete/form/{id}',                $ctrl.'@postDeleteForm');
        Route::get ('/create/',                         $ctrl.'@getCreateForm');
        Route::post('/create/',                         $ctrl.'@postCreateForm');
        Route::get ('/form-{id}/get-item/{idItem}',     $ctrl.'@getGetItem');
        Route::controller('/', $ctrl);
    });

    Route::group(array('prefix' => 'form-processor'), function()
    {
        $ctrl = 'FormProcessor';
        Route::post('/data',                            $ctrl.'@postData');
        Route::get('/file/display/{id}',                $ctrl.'@getFileDisplay');
        Route::get('/file/download/{id}',               $ctrl.'@getFileDownload');
        Route::get('/file/delete/{id}',                 $ctrl.'@getFileDelete');

        Route::controller('/', $ctrl);
    });

    Route::group(array('prefix' => 'index'), function()
    {
        $ctrl = 'Index';
        Route::get ('/{box}-from/{date_from}',          $ctrl.'@getSwitchDateFrom') -> where('box','summary|loggons') -> where ('date_from','today|last-week|last-month');
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'options-menu'), function() {
        $ctrl = 'OptionsMenu';
        Route::get('/create/{targetType}/{optionsMenu?}', $ctrl . '@getCreate')-> where('targetType','group|option');
        Route::post('/create/{targetType}/{optionsMenu?}', $ctrl . '@postCreate')-> where('targetType','group|option');
        Route::get('/edit/{optionsMenu}', $ctrl . '@getEdit');
        Route::get('/delete/{optionsMenu}', $ctrl . '@getDelete');
        Route::controller('/', $ctrl);
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


    Route::group(array('prefix' => 'headquarters'), function()
    {
        $ctrl = 'Headquarters';

        Route::get ('delete/{id}/modules-list',         $ctrl.'@getDeleteModulesList');

        Route::get ('manage-unit-modules/{id}',         $ctrl.'@getManageUnitModules');
        Route::post('manage-unit-modules/{id}',         $ctrl.'@postManageUnitModules');

        Route::get ('datatable',                        $ctrl.'@getDatatable');


        Route::get ('/edit/logo/{id}',                  $ctrl.'@getEditLogo');
        Route::post('/edit/logo/{id}',                  $ctrl.'@postEditLogo');

        Route::get ('/edit/{id}',                       $ctrl.'@getEdit');
        Route::post('/edit/{id}',                       $ctrl.'@postEdit');

        Route::get ('/create/',                         $ctrl.'@getCreate');
        Route::post('/create/',                         $ctrl.'@postCreate');

        Route::get ('/delete/{id}',                     $ctrl.'@getDelete');
        Route::get ('/active/{id}',                     $ctrl.'@getActive');

        Route::get ('/units',                          'Units@getIndex');
        Route::get ('/users',                          'Users@getIndex');

        Route::get ('/units/{id}',                      $ctrl.'@getUnits');
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'units'), function()
    {
        $ctrl = 'Units';

        Route::get ('tree-user-{id}.json',           $ctrl.'@getTreeJson');
        Route::get ('tree.json',                     $ctrl.'@getTreeJson');

        Route::get ('delete/{id}/modules-list',      $ctrl.'@getDeleteModulesList');
        Route::get ('manage-unit-modules/{id}',      $ctrl.'@getManageUnitModules');
        Route::post('manage-unit-modules/{id}',      $ctrl.'@postManageUnitModules');

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

    Route::group(array('prefix' => 'haccp-generic'), function()
    {
        $ctrl = 'HaccpGeneric';
        Route::get ('/create',                          $ctrl.'@getCreate');
        Route::post('/create',                          $ctrl.'@postCreate');
        Route::get ('/edit/{haccp}',                    $ctrl.'@getEdit');
        Route::post('/edit/{haccp}',                    $ctrl.'@postEdit');
        Route::get ('/active/{haccp}',                  $ctrl.'@getActive');
        Route::post('/edit/update',                     $ctrl.'@postUpdateOrder');
        Route::get ('/delete/{haccp}',                  $ctrl.'@getDelete');
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'knowledge-generic'), function()
    {
        $ctrl = 'KnowledgeGeneric';
        Route::get ('/create',                          $ctrl.'@getCreate');
        Route::post('/create',                          $ctrl.'@postCreate');
        Route::get ('/edit/{knowledge}',                $ctrl.'@getEdit');
        Route::post('/edit/{knowledge}',                $ctrl.'@postEdit');
        Route::get ('/active/{knowledge}',              $ctrl.'@getActive');
        Route::post('/edit/update',                     $ctrl.'@postUpdateOrder');
        Route::get ('/delete/{knowledge}',              $ctrl.'@getDelete');
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'menu-local'), function()
    {
        $ctrl = 'MenuLocal';
        Route::get ('/icons',                           $ctrl.'@getIcons');
        Route::get ('/create',                          $ctrl.'@getCreate');
        Route::post('/create',                          $ctrl.'@postCreate');
        Route::get ('/edit/{id}',                       $ctrl.'@getEdit');
        Route::post('/edit/{id}',                       $ctrl.'@postEdit');
        Route::get ('/active/{id}',                     $ctrl.'@getActive');
        Route::post('/edit/update',                     $ctrl.'@postUpdateOrder');
        Route::get ('/delete/{id}',                     $ctrl.'@getDelete');
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'menu-hq'), function()
    {
        $ctrl = 'MenuHq';
        Route::get ('/icons',                           $ctrl.'@getIcons');
        Route::get ('/create',                          $ctrl.'@getCreate');
        Route::post('/create',                          $ctrl.'@postCreate');
        Route::get ('/edit/{id}',                       $ctrl.'@getEdit');
        Route::post('/edit/{id}',                       $ctrl.'@postEdit');
        Route::get ('/active/{id}',                     $ctrl.'@getActive');
        Route::post('/edit/update',                     $ctrl.'@postUpdateOrder');
        Route::get ('/delete/{id}',                     $ctrl.'@getDelete');
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'menu-area'), function()
    {
        $ctrl = 'MenuArea';
        Route::get ('/icons',                           $ctrl.'@getIcons');
        Route::get ('/create',                          $ctrl.'@getCreate');
        Route::post('/create',                          $ctrl.'@postCreate');
        Route::get ('/edit/{id}',                       $ctrl.'@getEdit');
        Route::post('/edit/{id}',                       $ctrl.'@postEdit');
        Route::get ('/active/{id}',                     $ctrl.'@getActive');
        Route::post('/edit/update',                     $ctrl.'@postUpdateOrder');
        Route::get ('/delete/{id}',                     $ctrl.'@getDelete');
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'menu-visitor'), function()
    {
        $ctrl = 'MenuVisitor';
        Route::get ('/icons',                           $ctrl.'@getIcons');
        Route::get ('/create',                          $ctrl.'@getCreate');
        Route::post('/create',                          $ctrl.'@postCreate');
        Route::get ('/edit/{id}',                       $ctrl.'@getEdit');
        Route::post('/edit/{id}',                       $ctrl.'@postEdit');
        Route::get ('/active/{id}',                     $ctrl.'@getActive');
        Route::post('/edit/update',                     $ctrl.'@postUpdateOrder');
        Route::get ('/delete/{id}',                     $ctrl.'@getDelete');
        Route::get ('/',                                $ctrl.'@getIndex');
    });

    Route::group(array('prefix' => 'menu-admin'), function()
    {
        $ctrl = 'MenuAdmin';
        Route::get ('/icons',                           $ctrl.'@getIcons');
        Route::get ('/create',                          $ctrl.'@getCreate');
        Route::post('/create',                          $ctrl.'@postCreate');
        Route::get ('/edit/{id}',                       $ctrl.'@getEdit');
        Route::post('/edit/{id}',                       $ctrl.'@postEdit');
        Route::get ('/active/{id}',                     $ctrl.'@getActive');
        Route::post('/edit/update',                     $ctrl.'@postUpdateOrder');
        Route::get ('/delete/{id}',                     $ctrl.'@getDelete');
        Route::get ('/',                                $ctrl.'@getIndex');
    });


    Route::controller('/units-map',         'UnitsMap');
    Route::controller('/',                  'Index');

});