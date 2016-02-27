<?php

class KnowledgesData
{
    public static $modules = [
        'page'          => ['module' => 'ModulesPage',    'ico' => 'i-file2', 'path' => 'module-page'],
     /* 'articles'      => ['module' => 'ModulesArticles','ico' => 'i-stack2','path' => 'articles'],
        'contact-form'  => ['module' => 'ModulesContact', 'ico' => 'i-mail',  'path' => 'module-contact'],
        'video'         => ['module' => 'ModulesVideo',   'ico' => 'i-play',  'path' => 'module-video'],
        'gallery'       => ['module' => 'ModulesGallery', 'ico' => 'i-images','path' => 'module-gallery'],
        'map'           => ['module' => 'ModulesMap',     'ico' => 'i-map2',  'path' => 'module-map']*/
    ];

    public static $types = [
        'module'        => ['type' => 'modules'],
        'link'          => ['type' => 'redirect'],
        'firstChild'    => ['type' => 'redirect'],
    /*  'pageId'        => ['type' => 'redirect'] */
    ];

    public static function getModules()
    {
        return self::$modules;
    }

    public static function getModule($module)
    {
        return self::$modules[$module];
    }

    public static function getTypes()
    {
        return self::$types;
    }

    public static function getType($type)
    {
        return self::$types[$type];
    }

    public static function addLang($array)
    {
        foreach ($array as $key => $value){
            $array[$key]['lang'] = Lang::get('admin/'.__CLASS__.'/'.$key);
        }
        return $array;
    }

    public static function formData($array)
    {
        foreach ($array as $key => $value){
            $array[$key] = $value['lang'];
        }
        return $array;
    }
}