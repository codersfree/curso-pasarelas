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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'izipay' => [
        'url' => env('IZIPAY_URL'),
        'clien_id' => env('IZIPAY_CLIENT_ID'),
        'client_secret' => env('IZIPAY_CLIENT_SECRET'),
        'public_key' => env('IZIPAY_PUBLIC_KEY'),
        'hash_key' => env('IZIPAY_HASH_KEY'),
    ],

    'niubiz' => [
        'merchant_id' => env('NIUBIZ_MERCHANT_ID'),
        'currency' => env('NIUBIZ_CURRENCY'),
        'user' => env('NIUBIZ_USER'),
        'password' => env('NIUBIZ_PASSWORD'),
        'url_api' => env('NIUBIZ_URL_API'),
        'url_js' => env('NIUBIZ_URL_JS'),
    ],

    'paypal' => [
        'url' => env('PAYPAL_URL'),
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_SECRET'),
    ],

    'mercadopago' => [
        'public_key' => env('MERCADOPAGO_PUBLIC_KEY'),
        'access_token' => env('MERCADOPAGO_ACCESS_TOKEN'),
    ]
];
