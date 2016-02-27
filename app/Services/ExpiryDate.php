<?php
namespace Services;

class ExpiryDate extends \BaseController
{
    public function getConfigByTable($table)
    {
        return \Config::get('sections.'.$table.'.expiry') ? : [];
    }

    public static function setExpiryByTable(\Carbon $date, $table, $timezone = 'UTC')
    {
        $self = new self;
        $config = $self -> getConfigByTable($table) ? : [];
        if($config && isset($config['days']) && isset($config['time'])) {
            list($h, $m, $s) = explode(':', $config['time']);
            $date = $date->addDays($config['days'])->setTime($h, $m, $s);
        }
        return $date->timezone($timezone);
    }
}