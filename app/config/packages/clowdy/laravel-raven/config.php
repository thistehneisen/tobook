<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable raven logger
    |--------------------------------------------------------------------------
    |
    | Enable raven logger or not
    |
    */
    'enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Raven DSN
    |--------------------------------------------------------------------------
    |
    | Your project's DSN, found under 'API Keys' in your project's settings.
    |
    */

    'dsn' => 'https://fe0de91c9a1c43db821016b2183f53e8:26ca78fdb8584dcc8951c411556f884f@app.getsentry.com/34333',

    /*
    |--------------------------------------------------------------------------
    | Log Level
    |--------------------------------------------------------------------------
    |
    | Log level at which to log to Sentry. Default `error`.
    |
    | Available: 'debug', 'info', 'notice', 'warning', 'error',
    |            'critical', 'alert', 'emergency'
    |
    */

    'level' => 'error',

    /*
    |--------------------------------------------------------------------------
    | Monolog
    |--------------------------------------------------------------------------
    |
    | Customise the Monolog Raven handler.
    |
    */

    'monolog' => [

        /*
        |--------------------------------------------------------------------------
        | Processors
        |--------------------------------------------------------------------------
        |
        | Set extra data on every log made to Sentry.
        | Monolog has a number of built-in processors which you can find here:
        |
        | https://github.com/Seldaek/monolog/blob/master/README.mdown#processors
        |
        */

        'processors' => [
            // 'Monolog\Processor\GitProcessor'
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Raven Configuration
    |--------------------------------------------------------------------------
    |
    | Any values below will be passed directly as configuration to the Raven
    | instance. For more information about the possible values check
    | out: https://github.com/getsentry/raven-php
    |
    | Example: "name", "tags", "trace", "timeout", "exclude", "extra", ...
    |
    */

    'options' => []

];
