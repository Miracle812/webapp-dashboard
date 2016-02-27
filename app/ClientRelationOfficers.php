<?php
Route::group(array('namespace' => 'Sections\ClientRelationOfficers', 'before' => 'auth'), function()
{
    Route::get('/index',             ['as' => 'dashboard', 'uses' => 'Index@getIndex']);
});

Menu::make('Default', function($menu){
    $home = $menu->add('Dashboard', ['route'  => 'dashboard']);
        $home->prepend('<span class="menu_icon"><i class="uk-icon-small uk-icon-home"></i></span><span class="menu_title"> ')->append('</span>');

    $scrumBoard = $menu->add('Scrum board',['route'  => 'scrum-board.list']);  // URL: /about
        $scrumBoard->prepend('<span class="menu_icon"><i class="uk-icon-small uk-icon-tasks"></i></span><span class="menu_title"> ')->append('</span>');

    $workflow = $menu->add('Workflow',['route'  => 'workflow.list']);  // URL: /about
        $workflow->prepend('<span class="menu_icon"><i class="uk-icon-small uk-icon-refresh"></i></span><span class="menu_title"> ')->append('</span>');

    $calendar = $menu->add('Calendar',['route'  => 'calendar.list']);  // URL: /about
        $calendar->prepend('<span class="menu_icon"><i class="uk-icon-small uk-icon-calendar"></i></span><span class="menu_title"> ')->append('</span>');

    $clients = $menu->add('Contacts');  // URL: /about
        $clients->prepend('<span class="menu_icon"><i class="uk-icon-small uk-icon-users"></i></span><span class="menu_title"> ')->append('</span>');
        $clients->add('List', array('route'  => 'contacts.list'));

    $logout = $menu->add('Logout', array('url'  => 'logout'));  // URL: /about
        $logout->prepend('<span class="menu_icon"><i class="uk-icon-small uk-icon-sign-out"></i></span><span class="menu_title"> ')->append('</span>');
});
