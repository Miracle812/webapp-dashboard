<?php

$dns = App::environment('_app') ? 'https://32b8991728ff490ebb816689180c87a6:cfd1fbc7bc4248d6a36a31bcfed8d45e@app.getsentry.com/46664' : 'https://7f38dfa5b38b44c2a7d4c85ce18da0d8:cd6cacb5eca24b6ead1fc9bf4f18a042@app.getsentry.com/37578';
return [
    'raven' => [
        'dsn'   => $dns,
        'level' => 'debug',
        'tags' => [
            'env' => App::environment()
        ],
    ],
    'firebase' => array(
        'base_url' => 'https://navitest.firebaseio.com',
        'token' => 'cFwMCfC6dsS905vvjLQHFeMqp7CIXLdYcEaRHCAp'
    )
];