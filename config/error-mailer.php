<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Enable\disable error E-Mail alerts
    |--------------------------------------------------------------------------
    |
    | An option allowing to enable and disable sending email alerts in case an error occurs.
    |
    */
    'enabled' => env('ERROR_MAIL_ENABLE', false),

    /*
    |--------------------------------------------------------------------------
    | Minimal message severity to notify
    |--------------------------------------------------------------------------
    |
    | Minimal severity that should be notified about.
    |
    */
    'log_level' => env('ERROR_MAIL_LOG_LEVEL', 'error'),

    /*
    |--------------------------------------------------------------------------
    | Setting up E-Mail recipients and subject
    |--------------------------------------------------------------------------
    |
    | Allows to set up email subject for alert and recipients.
    | Recipients should be set up according to RFC 2822 standard
    |
    */
    'mail' => [
        'subject' => env('ERROR_MAIL_SUBJECT', 'Crash Report'),
        'to' => env('ERROR_MAIL_TO', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | The list of processors
    |--------------------------------------------------------------------------
    |
    | The list of processors that register in error handler. All processors of
    | monologue are supported. Callable interface should be implemented in order
    | to create your own processor. Initially, there are processors that are
    | commented out that can be useful, you can un-comment them if necessary.
    |
    */
    'processors' => [
        \BwtTeam\LaravelErrorMailer\Processors\SqlProcessor::class,
        \BwtTeam\LaravelErrorMailer\Processors\PostDataProcessor::class,
        \BwtTeam\LaravelErrorMailer\Processors\HeadersProcessor::class,
        \Monolog\Processor\WebProcessor::class,
        // \Monolog\Processor\GitProcessor::class,
        // \Monolog\Processor\MemoryUsageProcessor::class,
        // \Monolog\Processor\MemoryPeakUsageProcessor::class,
    ]
];