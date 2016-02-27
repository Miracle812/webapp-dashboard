<?php

return array(
    'driver' => 'smtp',
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'from' => array('address' => 'navitas.local.env@gmail.com', 'name' => 'Navitas Local Env'),
    'encryption' => 'tls',
    'username' => 'navitas.local.env',
    'password' => 'LNavitasEnv',
    'pretend' => false,
);
/*

return array(
    'driver' => 'sendmail',
    'host' => 'localhost',
    'port' => 25,
    'from' => array('address' => 'no-reply@local.test', 'name' => 'Test System'),
    'encryption' => null,
    'username' => '',
    'password' => '',
);
*/