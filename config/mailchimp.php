<?php

return [
    'api_keys' => [
        [
            'active' => true,
            'api_key' =>  env('MAILCHIMP_APIKEY'),
        ],
    ],
    'lists' => [
        'default' => env('MAILCHIMP_LIST_ID'),
    ],
];
