<?php

class Mapic {

    public static function getMenuParentActive($key) {
        $actKey = self::getActiveMenuKey($key);
        $menu = \Model\UsersMenuModel::getMenu();
        return $actKey ? self::getKeyParent($menu, $actKey)[0] : false;
    }

    public static function getActiveMenuKey($key) {

        $request_url = $_SERVER['REQUEST_URI'];
        return strpos(self::getRoutePath(),$key) ? $key : ( strpos($request_url,$key) ? $key : false );
    }

    public static function getRoutePath() {

        return Route::getCurrentRoute()->getPath();
    }

    public static function getKeyParent($array, $key) //not used yet but working good
    {
        if(in_array($key,array_keys($array))){
            return Array($key);
        }
        foreach($array as $k=>$v){
            if(isset($v['childs']) && in_array("childs",array_keys($v))){
                $result = self::getKeyParent($v["childs"], $key);
                if ($result !== null){
                    array_unshift($result,$k);
                    return $result;
                }
            }
        }
        return null;
    }

    public static function isValidTimestamp($timestamp)
    {
        $check = (is_int($timestamp) OR is_float($timestamp))
            ? $timestamp
            : (string) (int) $timestamp;
        if(
            ($check === $timestamp)
            AND ( (int) $timestamp <=  PHP_INT_MAX)
            AND ( (int) $timestamp >= ~PHP_INT_MAX)
            AND ( strlen($timestamp) >= 10)
        ){
            $dateNow = \Carbon::now();
            $dateTim = \Carbon::createFromTimestamp($timestamp);

            $diff = $dateTim->diffinDays($dateNow);
            return ($diff <= 3) ? true : false;
        }
        else{
            return false;
        }
    }

    public static function datatableFilter($inputs, $collection)
    {
        if($collection->count()) {
            $rules = [
                'limit' => 'numeric|max:100',
                'date_from' => 'date',
                'date_to' => 'date'
            ];
            $validator = \Validator::make($inputs, $rules);
            $errors = $validator->messages()->toArray();

            if (!isset($errors['date_from']) && $inputs['date_from']) {
                $start = \Carbon::createFromFormat('Y-m-d', $inputs['date_from'])->startOfDay();
                $collection = $collection->filter(function($item) use($start){
                    return ($item->created_at >= $start ? true : false);
                });
            }
            if (!isset($errors['date_to']) && $inputs['date_to']) {
                $end = \Carbon::createFromFormat('Y-m-d', $inputs['date_to'])->endOfDay();
                $collection = $collection->filter(function($item) use($end){
                    return ($item->created_at <= $end ? true : false);
                });
            }
            if (!isset($errors['limit']) && $inputs['limit']) {
                $collection = $collection->take($inputs['limit']);
            }else { $collection = $collection->take(100); }
        }

        return $collection;
    }

    public static function getPodApiSubver($subver)
    {
        $self = new self();
        $ver = $self -> getHubVersion();
        if($ver && isset($ver[1]) and is_numeric($ver[1]) and ($ver[1] > 0)){
            if(is_array($subver) && count($subver)){
                return (in_array($ver[1],$subver)) ? $ver[1] : end($subver);
            }
        }
        return null;
    }
    public static function getDroidApiSubver($subver)
    {
        $self = new self();
        $ver = $self -> getHubVersion();
        if($ver && isset($ver[1]) and is_numeric($ver[1]) and ($ver[1] > 0)){
            if(is_array($subver) && count($subver)){
                return (in_array($ver[1],$subver)) ? $ver[1] : end($subver);
            }
        }
        return null;
    }

    public static function checkHubAgentVer($version)
    {
        $self = new self();
        $ver = $self -> getHubVersion();
        if ($ver && isset($ver[0]) and is_numeric($ver[0]) and ($ver[0] > 0)) {
            return ((int)$version === (int)$ver[0]);
        }
        return false;
    }

    function getHubVersion()
    {
        if(\Agent::match('NavitasHub')) {
            $userAgent = \Request::Header('user-agent');
            $exp = explode('/', $userAgent);
            if (isset($exp[1])) {
                $ver = explode('.', $exp[1]);
                if(count($ver)==2){
                    return $ver;
                }
                elseif(count($ver)==1){
                    return ($ver + [1 => 0]);
                }
            }
        }
        return null;
    }
}