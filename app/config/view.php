<?php

$paths = [
    'general_paths' => [__DIR__.'/../views'],

    'panel_paths' => [
        __DIR__.'/../Sections/LocalManagers/Staff/views',
        __DIR__.'/../Sections/LocalManagers/Haccp/views',
        __DIR__.'/../Sections/LocalManagers/Suppliers/views',
        __DIR__.'/../Sections/LocalManagers/CheckList/views',
        __DIR__.'/../Sections/LocalManagers/Navinote/views',
        __DIR__.'/../Sections/LocalManagers/Profile/views',
        __DIR__.'/../Sections/LocalManagers/Temperatures/views',
        __DIR__.'/../Sections/LocalManagers/FoodIncidents/views',
        __DIR__.'/../Widgets/TemperaturesAlertBox/views',
        __DIR__.'/../Widgets/Calendar/views'
    ],

    'manager_paths' => [
        __DIR__.'/../Sections/HqManagers/Index/views',
        __DIR__.'/../Sections/HqManagers/Users/views',
        __DIR__.'/../Sections/HqManagers/Haccp/views',
        __DIR__.'/../Sections/HqManagers/Profile/views'
    ],

    'visitor_paths' => [
        __DIR__.'/../Sections/Visitors/Index/views',
        __DIR__.'/../Sections/Visitors/Haccp/views'
    ],

    'admin_paths' => [
        __DIR__.'/../Sections/Admins/Knowledge/views',
        __DIR__.'/../Sections/Admins/Haccp/views',
        __DIR__.'/../Sections/Admins/Users/views',
        __DIR__.'/../Sections/Admins/NavitasStructure/views'
    ],
];
if(isset($_SERVER['REQUEST_URI'])){
    $requestUri = $_SERVER['REQUEST_URI'];

    if(preg_match("/(^\\/manager)/",$requestUri))
    {
        $rolePaths = array_merge($paths['general_paths'], $paths['manager_paths']);
    }
    elseif(preg_match("/(^\\/panel)/",$requestUri))
    {
        $rolePaths = array_merge($paths['general_paths'], $paths['panel_paths']);
    }
    elseif(preg_match("/(^\\/admin)/",$requestUri))
    {
        $rolePaths = array_merge($paths['general_paths'], $paths['admin_paths']);
    }
    elseif(preg_match("/(^\\/visitor)/",$requestUri))
    {
        $rolePaths = array_merge($paths['general_paths'], $paths['visitor_paths']);
    }
    else {
        $rolePaths = $paths['general_paths'];
    }
}
else {
    $rolePaths = $paths['general_paths'];
}



return array(

	/*
	|--------------------------------------------------------------------------
	| View Storage Paths
	|--------------------------------------------------------------------------
	|
	| Most templating systems load templates from disk. Here you may specify
	| an array of paths that should be checked for your views. Of course
	| the usual Laravel view path has already been registered for you.
	|
	*/
    'paths' => $rolePaths,
    //'paths' => ['/../views'],

	/*
	|--------------------------------------------------------------------------
	| Pagination View
	|--------------------------------------------------------------------------
	|
	| This view will be used to render the pagination link output, and can
	| be easily customized here to show any view you like. A clean view
	| compatible with Bootstrap is given to you by default.
	|
	*/

	'pagination' => 'pagination::slider-3',

);
