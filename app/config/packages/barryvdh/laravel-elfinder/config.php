<?php

$ds = DIRECTORY_SEPARATOR;
$pp = public_path().$ds;
$user       = \Auth::user();
$dir        = 'upload/users';
$files_dir  = 'filemanager';
$dir_1 = $dir.$ds.$user->id;
$dir_2 = $dir_1.$ds.$files_dir;
if(!\File::exists($pp.$dir))
    \File::makeDirectory($pp.$dir);
if(!\File::exists($pp.$dir_1))
    \File::makeDirectory($pp.$dir_1);
if(!\File::exists($pp.$dir_2))
    \File::makeDirectory($pp.$dir_2);
$dir = $dir_2;

return array(

    /*
    |--------------------------------------------------------------------------
    | Upload dir
    |--------------------------------------------------------------------------
    |
    | The dir where to store the images (relative from public)
    |
    */

    'dir' => 'upload/users',
    'files_dir' => 'filemanager',

    /*
    |--------------------------------------------------------------------------
    | Access filter
    |--------------------------------------------------------------------------
    |
    | Filter callback to check the files
    |
    */

    'access' => 'Barryvdh\Elfinder\Elfinder::checkAccess',

    /*
    |--------------------------------------------------------------------------
    | Roots
    |--------------------------------------------------------------------------
    |
    | By default, the roots file is LocalFileSystem, with the above public dir.
    | If you want custom options, you can set your own roots below.
    |
    */
    'roots' => array(
        array(
            'driver' => 'LocalFileSystem',
            'path' => public_path() . DIRECTORY_SEPARATOR . $dir,
            'URL' => asset($dir),
            'alias' => 'My volume ('.$user->fullname().')',
            'accessControl' => 'Barryvdh\Elfinder\Elfinder::checkAccess'
        )
    ),
    /*
    |--------------------------------------------------------------------------
    | CSRF
    |--------------------------------------------------------------------------
    |
    | CSRF in a state by default false.
    | If you want to use CSRF it can be replaced with true (boolean).
    |
    */

    'csrf'=>true,

);
