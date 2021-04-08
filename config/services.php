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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'vcc' => [
        'client_id' => env('VCC_CLIENT_ID'),
        'client_secret' => env('VCC_CLIENT_SECRET'),
        'redirect' => env('VCC_CLIENT_CALLBACK'),
    ],

    'vtcm-web-app' => [
        'redirect' => env('VTCM_WEB_APP_URL'),
    ],

    'vcc-client-auth' => [
        'redirect_to_url' => env('VCC_VTCM_CLIENT_REDIRECT'),
        'client_secret' => env('VCC_VTCM_CLIENT_SECRET'),
        'client_id' => env('VCC_VTCM_CLIENT_ID'),
        'redirect' => env('VCC_VTCM_CLIENT_CALLBACK'),
    ],

];
