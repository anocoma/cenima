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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
        'client_id' => '1191806508754166',
        'client_secret' => '1cdae081c9bd6ba5cd5fff569e7912a7',
        'redirect' => env('APP_URL') . '/auth/facebook/callback',
    ],
    /*'google' => [
        'client_id' => "484709232894-5vvb3cg57qggodm9v6hgbdvcd6o4n34i.apps.googleusercontent.com",
        'client_secret' => "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",
        'redirect' =>  "http://datn-hn5.me/auth/google/callback",
    ],*/
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID', '484709232894-5vvb3cg57qggodm9v6hgbdvcd6o4n34i.apps.googleusercontent.com'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET','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'),
        'redirect' => 'http://datn-hn5.me/auth/google/callback',
    ],
    # Google Auth ENV
    /*GOOGLE_CLIENT_ID=484709232894-5vvb3cg57qggodm9v6hgbdvcd6o4n34i.apps.googleusercontent.com
    GOOGLE_CLIENT_SECRET=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
    GOOGLE_REDIRECT_URI=http://datn-hn5.me/auth/google/callback*/

];
