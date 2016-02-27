<?php namespace Model;

class TemperaturesLogInvalid extends TemperaturesModel {
    var $timestamps = false,
        $table = 'temperatures_log_invalid';
    protected $fillable = [
        'rules_id',
        'type',
        'exceed',
        'temperature',
    ];
}
