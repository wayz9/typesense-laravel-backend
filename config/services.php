<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'typesense' => [
        'api_key' => env('TYPESENSE_API_KEY'),
        'host' => env('TYPESENSE_HOST', 'localhost'),
        'port' => env('TYPESENSE_PORT', 8108),
        'protocol' => env('TYPESENSE_PROTOCOL', 'http'),
    ],
];
