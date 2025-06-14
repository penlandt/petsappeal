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

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'connect_client_id' => env('STRIPE_CONNECT_CLIENT_ID'),

        // Old pricing structure (can be removed if no longer used)
        'price_starter' => env('STRIPE_PRICE_STARTER'),
        'price_pro' => env('STRIPE_PRICE_PRO'),
        'price_multi' => env('STRIPE_PRICE_MULTI'),
        'price_starter_annual' => env('STRIPE_PRICE_STARTER_ANNUAL'),
        'price_pro_annual' => env('STRIPE_PRICE_PRO_ANNUAL'),
        'price_multi_annual' => env('STRIPE_PRICE_MULTI_ANNUAL'),

        // New modular pricing structure
        'modules' => [
            'grooming' => [
                'monthly' => env('STRIPE_PRICE_MODULE_GROOMING'),
                'annual' => env('STRIPE_PRICE_MODULE_GROOMING_ANNUAL'),
            ],
            'boarding' => [
                'monthly' => env('STRIPE_PRICE_MODULE_BOARDING'),
                'annual' => env('STRIPE_PRICE_MODULE_BOARDING_ANNUAL'),
            ],
            'daycare' => [
                'monthly' => env('STRIPE_PRICE_MODULE_DAYCARE'),
                'annual' => env('STRIPE_PRICE_MODULE_DAYCARE_ANNUAL'),
            ],
            'sitting' => [
                'monthly' => env('STRIPE_PRICE_MODULE_SITTING'),
                'annual' => env('STRIPE_PRICE_MODULE_SITTING_ANNUAL'),
            ],
        ],

        'support' => [
            'chat' => [
                'monthly' => env('STRIPE_PRICE_SUPPORT_CHAT'),
                'annual' => env('STRIPE_PRICE_SUPPORT_CHAT_ANNUAL'),
            ],
            'phone' => [
                'monthly' => env('STRIPE_PRICE_SUPPORT_PHONE'),
                'annual' => env('STRIPE_PRICE_SUPPORT_PHONE_ANNUAL'),
            ],
        ],
    ],

    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    ],

];
