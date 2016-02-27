<?php
Route::group(array('namespace' => 'Sections\LocalManagers', 'before' => 'auth'), function()
{
    Route::group(array('prefix' => 'data'), function() {
        Route::get('dashboard_temps_widget_{id}.json','Index@getTempsWidgetData');
        Route::get('dashboard_dtot_schedules.json','Index@getDtSchedules');
        Route::get('dashboard_dtot_checklist.json','Index@getDtChecklist');
        Route::get('dashboard_dtot_compliancediary.json','Index@getDtComplianceDiary');
        Route::get('dashboard_dtot_probes.json','Index@getDtProbes');
        Route::get('dashboard_dtot_pods.json','Index@getDtPods');
        Route::post('dashboard_dt_area_last_temps.json','Index@postDtLastTemps');
    });

    Route::group(array('prefix' => 'site-haccp'), function()
    {
        $ctrl = 'HaccpSite';
        Route::get ('/create',          $ctrl.'@getCreate');
        Route::post('/create',          $ctrl.'@postCreate');
        Route::get ('/edit/{haccp}',    $ctrl.'@getEdit');
        Route::post('/edit/{haccp}',    $ctrl.'@postEdit');
        Route::get ('/active/{haccp}',  $ctrl.'@getActive');
        Route::post('/edit/update',     $ctrl.'@postUpdateOrder');
        Route::get ('/delete/{haccp}',  $ctrl.'@getDelete');
        Route::get ('/',                ['as' => 'haccp.site.settings', 'uses' => $ctrl.'@getIndex']);
    });

    Route::group(array('prefix' => 'site-knowledge'), function()
    {
        $ctrl = 'KnowledgeSite';
        Route::get ('/create',             $ctrl.'@getCreate');
        Route::post('/create',             $ctrl.'@postCreate');
        Route::get ('/edit/{knowledge}',   $ctrl.'@getEdit');
        Route::post('/edit/{knowledge}',   $ctrl.'@postEdit');
        Route::get ('/active/{knowledge}', $ctrl.'@getActive');
        Route::post('/edit/update',        $ctrl.'@postUpdateOrder');
        Route::get ('/delete/{knowledge}', $ctrl.'@getDelete');
        Route::get ('/',                   ['as' => 'knowledge.site.settings', 'uses' => $ctrl.'@getIndex']);
    });

    Route::group(array('prefix' => 'probes'), function()
    {
        Route::group(array('prefix' => 'areas'), function ()
        {
            $ctrl = 'ProbesAreas';
            Route::get ('/create',      $ctrl . '@getCreate');
            Route::post('/create',      $ctrl . '@postCreate');
            Route::get ('/edit/{id}',   $ctrl . '@getEditArea');
            Route::post('/edit/{id}',   $ctrl . '@postEditArea');
            Route::get ('/delete/{id}', $ctrl . '@getDelete');
            Route::get ('/list',        $ctrl . '@getAreasList');
            Route::get ('/',            ['as' => 'probe.areas.settings', 'uses' => $ctrl.'@getIndex']);
        });

        Route::group(array('prefix' => 'devices'), function()
        {
            $ctrl = 'ProbesDevices';
            Route::get ('/edit/activate/{id}', $ctrl.'@getActivate');
            Route::get ('/edit/{id}',          $ctrl.'@getEdit');
            Route::post('/edit/{id}',          $ctrl.'@postEdit');
            Route::get ('/delete/{id}',        $ctrl.'@getDeleteDevice');
            Route::get ('/',                   ['as' => 'probe.devices.settings', 'uses' => $ctrl.'@getIndex']);
        });

        Route::group(array('prefix' => 'menu-items'), function()
        {
            $ctrl = 'ProbesMenuItems';
            Route::post('/edit/update', $ctrl.'@postUpdate');
            Route::get ('/',            ['as' => 'probe.menu.settings', 'uses' => $ctrl.'@getIndex']);
        });
    });

    Route::group(array('prefix' => 'pods'), function()
    {
        Route::group(array('prefix' => 'areas'), function()
        {
            $ctrl = 'PodsAreas';
            Route::get ('/refresh',           $ctrl.'@getRefresh');
            Route::post('/update',            $ctrl.'@postUpdateOrder');
            Route::get ('/create/{type?}',    $ctrl.'@getCreate');
            Route::post('/create/{type?}',    $ctrl.'@postCreate');
            Route::get ('/edit/{podsArea}',   $ctrl.'@getEdit');
            Route::post('/edit/{podsArea}',   $ctrl.'@postEdit');
            Route::get ('/delete/{podsArea}', $ctrl.'@getDelete');
            Route::get ('/',                  ['as' => 'pod.areas.settings', 'uses' => $ctrl.'@getIndex']);
        });
        Route::group(array('prefix' => 'sensors'), function()
        {
            $ctrl = 'PodsSensors';
            Route::get ('/create',           $ctrl.'@getCreate');
            Route::post ('/create',          $ctrl.'@postCreate');
            Route::get ('/delete/{id}',      $ctrl.'@getDelete');
            Route::get ('/edit/{id}',        $ctrl.'@getEdit');
            Route::post('/edit/{id}',        $ctrl.'@postEdit');
            Route::get ('/load-areas/{id?}', $ctrl.'@getLoadAreas');
            Route::get ('/datatable',        $ctrl.'@getDatatable');
            Route::get ('/',                 ['as' => 'pod.devices.settings', 'uses' => $ctrl.'@getIndex']);
        });
    });

    Route::group(array('prefix' => 'temperatures-alert-box'), function()
    {
        $ctrl = 'TemperaturesAlertBox';
        Route::get ('/create/folder',      $ctrl.'@getCreateFolder');
        Route::post('/create/folder',      $ctrl.'@postCreateFolder');
        Route::get ('/delete/folder/{id}', $ctrl.'@getDeleteFolder');
        Route::get ('/create/area/{id}',   $ctrl.'@getCreateArea');
        Route::post('/create/area/{id}',   $ctrl.'@postCreateArea');
        Route::get ('/delete/area/{id}',   $ctrl.'@getDeleteArea');
        Route::get ('/edit/{id}',          $ctrl.'@getEdit');
        Route::post('/edit/{id}',          $ctrl.'@postEdit');
        Route::get ('/folder/{id}',        $ctrl.'@getAreas');
        Route::get ('/load-areas/{group}', $ctrl.'@getLoadAreas');
        Route::get ('/',                   ['as' => 'temperatures_widget.settings', 'uses' => $ctrl.'@getIndex']);
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
        Route::get ('/{id?}',                           ['as' => 'staff.index', 'uses' => $ctrl.'@getIndex']);
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
        Route::get ('/',                                ['as' => 'health_questionnaires.index', 'uses' => $ctrl.'@getIndex']);
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
        Route::get ('/',                                ['as' => 'suppliers.index', 'uses' => $ctrl.'@getIndex']);
    });

    Route::group(array('prefix' => 'trainings'), function()
    {
        $ctrl = 'Trainings';
        Route::controller('/', $ctrl);
        Route::get ('/',                   ['as' => 'training_records.index', 'uses' => $ctrl.'@getIndex']);
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
        Route::get ('/',                                ['as' => 'goods_in_records.index', 'uses' => $ctrl.'@getIndex']);
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
        Route::get ('/',                                ['as' => 'food_incident_records.index', 'uses' => $ctrl.'@getIndex']);
    });

    Route::group(array('prefix' => 'temperatures'), function()
    {
        $ctrl = 'Temperatures';

        Route::group(array('prefix' => 'forms'), function() use($ctrl) {
            Route::get('/submitted',              $ctrl . '@getFormsSubmittedList');
            Route::get('/submitted/{id}/details', $ctrl . '@getFormsSubmittedDetails');
            Route::get('/datatable',              $ctrl . '@getFormsDatatable');
            Route::get('/',                       ['as' => 'temperatures.forms', 'uses' => $ctrl.'@getFormsIndex']);
        });
        Route::get ('/create',                          $ctrl.'@getCreate') ;
        Route::post('/create',                          $ctrl.'@postCreate') ;
        Route::get ('/datatable/{group}/{id}/{date?}',  $ctrl.'@getDatatableArea');
        Route::get ('/datatable/{group}/{date?}',       $ctrl.'@getDatatableAreas');

        Route::get ('/{group}/resolve/area/{id}',            $ctrl.'@getResolveAreaTemperatures')->where('group','pods|probes');
        Route::post('/{group}/resolve/area/{id}',            $ctrl.'@postResolveAreaTemperatures')->where('group','pods|probes');

        Route::get ('/{group}/{id}/{date?}',            ['as' => 'temperatures.groups.area', 'uses' => $ctrl.'@getAreaTemperatures'])->where('group','pods|probes');
        Route::get ('/{group}/{date?}',                 ['as' => 'temperatures.groups', 'uses' => $ctrl.'@getAreasTemperatures'])->where('group','pods|probes');
        Route::get ('/',                                ['as' => 'temperatures.index', 'uses' => $ctrl.'@getIndex']);
    });

    Route::group(array('prefix' => 'haccp'), function()
    {
        $ctrl = 'Haccp';
        Route::group(array('prefix' => 'storage'), function() use($ctrl) {
            Route::get('/item/{haccp}',           $ctrl . '@getStorageItem');
            Route::get('/',                       ['as' => 'haccp.index', 'uses' => $ctrl.'@getStorageIndex']);
        });
        Route::group(array('prefix' => 'forms'), function() use($ctrl) {
            Route::get('/submitted',              $ctrl . '@getFormsSubmittedList');
            Route::get('/submitted/{id}/details', $ctrl . '@getFormsSubmittedDetails');
            Route::get('/datatable',              $ctrl . '@getFormsDatatable');
            Route::get('/',                       ['as' => 'haccp.forms', 'uses' => $ctrl.'@getFormsIndex']);
        });
        Route::get('/',                           ['as' => 'haccp.index', 'uses' => $ctrl.'@getStorageIndex']);
    });

    Route::group(array('prefix' => 'knowledge'), function()
    {
        $ctrl = 'Knowledge';
        Route::group(array('prefix' => 'storage'), function() use($ctrl)
        {
            Route::get ('/item/{knowledge}',      $ctrl . '@getStorageItem');
            Route::get ('/pdf/{knowledge}',       $ctrl . '@getStoragePdf');
            Route::get('/',                       ['as' => 'knowledge.index', 'uses' => $ctrl.'@getStorageIndex']);
        });
        Route::group(array('prefix' => 'forms'), function() use($ctrl) {
            Route::get('/submitted',              $ctrl . '@getFormsSubmittedList');
            Route::get('/submitted/{id}/details', $ctrl . '@getFormsSubmittedDetails');
            Route::get('/datatable',              $ctrl . '@getFormsDatatable');
            Route::get('/',                       ['as' => 'knowledge.forms', 'uses' => $ctrl.'@getFormsIndex']);
        });
        Route::get('/',                           ['as' => 'knowledge.index', 'uses' => $ctrl.'@getStorageIndex']);
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
        Route::get ('/',                                ['as' => 'navinotes.index', 'uses' => $ctrl.'@getIndex']);
    });

    Route::group(array('prefix' => 'reports'), function()
    {
        $ctrl = 'Reports';
        Route::post ('/',                               $ctrl.'@postCreate');
        Route::get ('/',                                ['as' => 'reports.index', 'uses' => $ctrl.'@getIndex']);
    });

    Route::group(array('prefix' => 'searching'), function()
    {
        $ctrl = 'Searching';
        Route::post ('/',                          $ctrl.'@postFind');
    });

    Route::get('/index', ['as' => 'dashboard', 'uses' => 'Index@getIndex']);
});

Menu::make('Default', function($menu){
    $home = $menu->add('Dashboard', ['route'  => 'dashboard']);
        $home->prepend('<span class="menu_icon"><i class="material-icons">home</i></span><span class="menu_title"> ')->append('</span>');

//settings menu
    $settings = $menu->add('Settings');
        $settings->prepend('<span class="menu_icon"><i class="material-icons">settings</i></span><span class="menu_title"> ')->append('</span>');
        $settings->add('Site Haccp',     array('route'  => 'haccp.site.settings'));
        $settings->add('Site Knowledge', array('route'  => 'knowledge.site.settings'));
        $probeSettings = $settings->add('Probe Settings');
            $probeSettings->add('Probe Areas',      array('route'  => 'probe.areas.settings'));
            $probeSettings->add('Probe Devices',    array('route'  => 'probe.devices.settings'));
            $probeSettings->add('Probe Menu Items', array('route'  => 'probe.menu.settings'));
        $podSettings   = $settings->add('Pod Settings');
            $podSettings->add('Pod Areas',   array('route'  => 'pod.areas.settings'));
            $podSettings->add('Pod Devices', array('route'  => 'pod.devices.settings'));
       $settings->add('Widgets',     array('route'  => 'temperatures_widget.settings'));
//settings menu

    $records = $menu->add('Records');
        $records->prepend('<span class="menu_icon"><i class="material-icons">folder_open</i></span><span class="menu_title"> ')->append('</span>');
        $records->add('Staff',                         array('route'  => 'staff.index'));
        $records->add('Health Questionnaires',         array('route'  => 'health_questionnaires.index'));
        $records->add('Training Records',              array('route'  => 'training_records.index'));
        $records->add('Suppliers',                     array('route'  => 'suppliers.index'));
        $records->add('Goods In Record',               array('route'  => 'goods_in_records.index'));
        $records->add('Food Incident Record',          array('route'  => 'food_incident_records.index'));
        $cleaning = $records->add('Cleaning Schedule',    array('route'  => 'cleaning_schedules.index'));
            $cleaning->add('Calendar',                    array('route'  => 'cleaning_schedules.index'));
            $cleaning->add('Forms',                       array('route'  => 'cleaning_schedules.forms'));
            $cleaning->add('Submitted',                   array('route'  => 'cleaning_schedules.submitted'));
        $compliance = $records->add('Compliance Diary',   array('route'  => 'compliance_diary.index'));
            $compliance->add('Calendar',                  array('route'  => 'compliance_diary.index'));
            $compliance->add('Submitted',                 array('route'  => 'compliance_diary.submitted'));
        $checklist = $records->add('Check List',          array('route'  => 'compliance_diary.index'));
            $checklist->add('Calendar',                   array('route'  => 'compliance_diary.index'));
            $checklist->add('Forms',                      array('route'  => 'check_list.forms'));
            $checklist->add('Submitted',                  array('route'  => 'check_list.submitted'));

    $temperatures = $menu->add('Temperatures');
        $temperatures->prepend('<span class="menu_icon"><i class="material-icons">tune</i></span><span class="menu_title"> ')->append('</span>');
            $probe = $temperatures->add('Probe temperatures',   array('route' => array('temperatures.groups','group'=>'probes')));
                $probe->add('Chilling',                         array('route' => array('temperatures.groups.area','group'=>'probes','id'=>1)));
                $probe->add('Re heating',                       array('route' => array('temperatures.groups.area','group'=>'probes','id'=>5)));
                $probe->add('Cooking',                          array('route' => array('temperatures.groups.area','group'=>'probes','id'=>2)));
                $probe->add('Hot Service',                      array('route' => array('temperatures.groups.area','group'=>'probes','id'=>4)));
                $probe->add('Cold Service',                     array('route' => array('temperatures.groups.area','group'=>'probes','id'=>3)));
        $temperatures->add('Pod temperatures',                  array('route' => array('temperatures.groups','group'=>'pods')));
        $temperatures->add('Temperatures Forms',                array('route'  => 'temperatures.forms'));

    $knowledge = $menu->add('Knowledge');
        $knowledge->prepend('<span class="menu_icon"><i class="material-icons">library_books</i></span><span class="menu_title"> ')->append('</span>');
        $knowledge->add('Knowledge',                  array('route'  => 'knowledge.index'));
        $knowledge->add('Knowledge Forms',            array('route'  => 'knowledge.forms'));

    $haccp = $menu->add('Haccp');
        $haccp->prepend('<span class="menu_icon"><i class="material-icons">warning</i></span><span class="menu_title"> ')->append('</span>');
        $haccp->add('Haccp',                  array('route'  => 'haccp.index'));
        $haccp->add('Haccp Forms',            array('route'  => 'haccp.forms'));

    $reports = $menu->add('Reports',          array('route'  => 'reports.index'));
        $reports->prepend('<span class="menu_icon"><i class="material-icons">content_paste</i></span><span class="menu_title"> ')->append('</span>');

    $navinotes = $menu->add('Navinotes',          array('route'  => 'navinotes.index'));
        $navinotes->prepend('<span class="menu_icon"><i class="material-icons">speaker_notes</i></span><span class="menu_title"> ')->append('</span>');

    $support = $menu->add('Support',          array('route'  => 'support_system.index'));
        $support->prepend('<span class="menu_icon"><i class="material-icons">verified_user</i></span><span class="menu_title"> ')->append('</span>');

    $logout = $menu->add('Logout', array('url'  => 'logout'));  // URL: /about
        $logout->prepend('<span class="menu_icon"><i class="material-icons">power_settings_new</i></span><span class="menu_title"> ')->append('</span>');
});
