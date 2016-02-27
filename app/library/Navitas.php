<?php

class Navitas {

    public static $probe;

    public static  function getHourlyKeyByData($data){
        //$UkTz = new DateTimeZone("Europe/London");
        //$date = new DateTime('now', $UkTz );
        //$time = $date -> format('Y-m-d H:00:00');
        //$miliseconds = strtotime($time)*1000;
        //return md5($data.$miliseconds);
        return md5($data);
    }
}
