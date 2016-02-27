<?php
Route::group(array('namespace' => 'Sections\Accountants', 'before' => 'auth|detectLang|detectTimezone'), function()
{
    Route::group(array('prefix' => 'invoices'), function()
    {
        $ctrl = 'Invoices';
        Route::any('/datatable.json', ['as' => 'invoices.datatable', 'uses' => $ctrl.'@datatable']);
        Route::get ('/{list?}',       ['as' => 'invoices.list',    'uses'=>  $ctrl.'@getIndex'])->where('list','list');
    });
    Route::group(array('prefix' => 'contacts'), function()
    {
        $ctrl = 'Contacts';
        Route::any('/datatable.json',['as' => 'contacts.datatable', 'uses' => $ctrl.'@datatable']);
        Route::get ('/{list?}',      ['as' => 'contacts.list', 'uses'=>  $ctrl.'@getIndex'])->where('list','list');
    });
    Route::get('/index',             ['as' => 'dashboard', 'uses' => 'Index@getIndex']);
});

Menu::make('Default', function($menu){
    $home = $menu->add('Dashboard', ['route'  => 'dashboard']);
        $home->prepend('<span class="menu_icon"><i class="uk-icon-small uk-icon-home"></i></span><span class="menu_title"> ')->append('</span>');

    $invoices = $menu->add('Invoices');  // URL: /about
        $invoices->prepend('<span class="menu_icon"><i class="uk-icon-small uk-icon-file-text-o"></i></span><span class="menu_title"> ')->append('</span>');
        $invoices->add('List', array('route'  => 'invoices.list'));

    $clients = $menu->add('Contacts');  // URL: /about
        $clients->prepend('<span class="menu_icon"><i class="uk-icon-small uk-icon-users"></i></span><span class="menu_title"> ')->append('</span>');
        $clients->add('List', array('route'  => 'contacts.list'));


    $logout = $menu->add('Logout', array('url'  => 'logout'));  // URL: /about
        $logout->prepend('<span class="menu_icon"><i class="uk-icon-small uk-icon-sign-out"></i></span><span class="menu_title"> ')->append('</span>');
});
