<?php
namespace Services;

class Breadcrumbs extends \BaseController {

    public $breadcrumbs = [];
    public $active;

    public function __construct(){
        $this->breadcrumbs[] = $this -> addFirstCrumb();
    }

    public function addFirstCrumb()
    {
        return ['ico'=>'fa fa-home','path'=>'/','name'=>\Lang::get('/common/sections.home')];
    }

    public function addCrumb($path, $name, $ico = 'fa fa-list-ul')
    {
        $this -> breadcrumbs[] =  ['ico' => $ico, 'path' => $path, 'name' => $name];
        return $this;
    }

    public function addLast($name)
    {
        $this -> breadcrumbs[] =  ['ico'=>'', 'path'=>'', 'name'=>$name];
        return $this -> breadcrumbs;
    }

    public function getBreadcrumb()
    {
       return $this -> breadcrumbs;
    }

    function searchAndReplace($search, $replace){
        preg_match_all("/\{(.+?)\}/", $search, $matches);
        if (isset($matches[1]) && count($matches[1]) > 0){
            foreach ($matches[1] as $key => $value) {
                if (array_key_exists(strtolower($value), $replace)){
                    $search = preg_replace("/\{$value\}/", $replace[strtolower($value)], $search);
                }
            }
        }
        return $search;
    }

    public function getBreadcrumbs($items, $paths){

        foreach($items as $item){
            if(!$item) continue;
            foreach($paths as $key => $path){
                $return = $this->searchAndReplace($path,$item);
            }
            $data[] = ['name'=>$item['name'], $key=>$return];

        }
    return isset($data)?$data:false;
    }
}