<?php

Event::listen('auth.login', function($user)
{
    if (Auth::check())
    {
        $isExist = \Model\UsersStatistics::
              where('session_id','=',Session::getId())
            ->where('user_id','=',$user -> id)
            ->where('action','=','log_in')
            ->first();
        if(!$isExist){
            $log = new \Model\UsersStatistics;
            $log -> user_id = $user -> id;
            $log -> session_id = Session::getId();
            $log -> ip = Request::getClientIp();
            $log -> agent = $_SERVER['HTTP_USER_AGENT'];
            $log -> action = 'log_in';
            $log -> role = $user->role()->name;
            $log -> save();
        }
    }

    if(Auth::viaRemember()){
       //$event = Event::fire('auth.cookieLogin', array($user));
    }
});

Event::listen('auth.cookieLogin', function($user)
{

});


Event::listen('auth.logout', function()
{
    if(Auth::check()){
        $user = Auth::user();
        $userId = $user -> id;
        $isExist = \Model\UsersStatistics::
            where('session_id','=',Session::getId())
            ->where('action','=','log_out')
            ->where('user_id','=',$userId)
            ->first();
        if(!$isExist){
            $log = new \Model\UsersStatistics;
            $log -> user_id    = $userId;
            $log -> session_id = Session::getId();
            $log -> ip         = Request::getClientIp(true);
            $log -> agent = $_SERVER['HTTP_USER_AGENT'];
            $log -> action = 'log_out';
            $log -> role = $user->role()->name;
            $log -> save();
        }
    }
});
?>