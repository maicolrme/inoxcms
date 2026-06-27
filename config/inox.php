<?php

return [
    'name' => env('INOX_NAME', 'INOX'),
    'version' => '0.1.0',
    'status' => env('INOX_STATUS', 'alpha'),

    'project_type' => env('INOX_PROJECT_TYPE', 'website'),

    'installer' => [
        'step' => env('INOX_INSTALL_STEP', 'welcome'),
        'completed' => env('INOX_INSTALL_COMPLETED', false),
    ],

    'database' => [
        'default' => env('INOX_DB_DRIVER', 'sqlite'),
    ],

    'storage' => [
        'default' => env('INOX_STORAGE_DRIVER', 'local'),
    ],

    'cache' => [
        'page' => [
            'enabled' => env('INOX_CACHE_PAGE', false),
            'driver' => env('INOX_CACHE_DRIVER', 'file'),
        ],
        'object' => [
            'enabled' => env('INOX_CACHE_OBJECT', false),
        ],
        'fragment' => [
            'enabled' => env('INOX_CACHE_FRAGMENT', false),
        ],
    ],

    'modules' => [
        'path' => base_path('modules'),
        'active' => ['storage', 'api', 'schema-studio'],
    ],

    'themes' => [
        'path' => base_path('themes'),
        'active' => 'inox/simple',
    ],

    'features' => [
        'realtime' => env('INOX_FEATURE_REALTIME', false),
        'ai' => env('INOX_FEATURE_AI', false),
    ],
];
