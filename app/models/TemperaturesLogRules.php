<?php namespace Model;

class TemperaturesLogRules extends TemperaturesModel {
    var $timestamps = false;
    protected $fillable = [
        'warning_min',
        'warning_max',
        'valid_min',
        'valid_max',
    ];
}
