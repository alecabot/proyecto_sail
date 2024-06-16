<?php

return [
    'name' => 'LaravelPWA',
    'manifest' => [
        'name' => env('APP_NAME', 'My PWA App'),
        'short_name' => 'PWA',
        "start_url" => '/',
        'background_color' => '#ffffff',
        'theme_color' => '#000000',
        'display' => 'standalone',
        'orientation'=> 'any',
        'status_bar'=> 'black',
        'icons' => [
            '72x72' => [
                'path' => '/img/LogotipoApp.png',
                'purpose' => 'any'
            ],
            '96x96' => [
                'path' => '/img/LogotipoApp.png',
                'purpose' => 'any'
            ],
            '128x128' => [
                'path' => '/img/LogotipoApp.png',
                'purpose' => 'any'
            ],
            '144x144' => [
                'path' => '/img/LogotipoApp.png',
                'purpose' => 'any'
            ],
            '152x152' => [
                'path' => '/img/LogotipoApp.png',
                'purpose' => 'any'
            ],
            '192x192' => [
                'path' => '/img/LogotipoApp.png',
                'purpose' => 'any'
            ],
            '384x384' => [
                'path' => '/img/LogotipoApp.png',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => '/img/LogotipoApp.png',
                'purpose' => 'any'
            ],
        ],
        'splash' => [
            '640x1136' => '/img/LogotipoApp.png',
            '750x1334' => '/img/LogotipoApp.png',
            '828x1792' => '/img/LogotipoApp.png',
            '1125x2436' => '/img/LogotipoApp.png',
            '1242x2208' => '/img/LogotipoApp.png',
            '1242x2688' => '/img/LogotipoApp.png',
            '1536x2048' => '/img/LogotipoApp.png',
            '1668x2224' => '/img/LogotipoApp.png',
            '1668x2388' => '/img/LogotipoApp.png',
            '2048x2732' => '/img/LogotipoApp.png',
        ],
        'shortcuts' => [
            [
                'name' => 'Shortcut Link 1',
                'description' => 'Shortcut Link 1 Description',
                'url' => '/shortcutlink1',
                'icons' => [
                    "src" => "/img/LogotipoApp.png",
                    "purpose" => "any"
                ]
            ],
            [
                'name' => 'Shortcut Link 2',
                'description' => 'Shortcut Link 2 Description',
                'url' => '/shortcutlink2'
            ]
        ],
        'custom' => []
    ]
];
