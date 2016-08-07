<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Widget Namespace
    |--------------------------------------------------------------------------
    |
    | Default widget namespace.
    |
     */

    'namespace' => 'ZEDx\Widgets',

    /*
    |--------------------------------------------------------------------------
    | Widget Path
    |--------------------------------------------------------------------------
    |
    | Default widget path.
    |
     */

    'path' => base_path().'/'.'widgets',

    /*
    |--------------------------------------------------------------------------
    | Widget Stubs
    |--------------------------------------------------------------------------
    |
    | Default widget stubs.
    |
     */
    'stubs' => [
        'path'    => core_src_path().'/Console/Commands/Widget/stubs',
        'folders' => ['views', 'assets'],
        'files'   => [
            'json'          => 'zedx.json',
            'views/index'   => 'views/index.blade.php',
            'views/setting' => 'views/setting.blade.php',
            'widget'        => 'Widget.php',
        ],
        'replacements' => [
            'views/index' => ['STUDLY_NAME'],
            'json'        => [
                'STUDLY_TYPE',
                'LOWER_TYPE',
                'STUDLY_NAME',
                'STUDLY_AUTHOR',
                'LOWER_NAME',
            ],
            'widget' => [
                'LOWER_TYPE',
                'LOWER_AUTHOR',
                'LOWER_NAME',
                'STUDLY_TYPE',
                'STUDLY_AUTHOR',
                'STUDLY_NAME',
            ],
        ],
    ],
    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Widgets path
        |--------------------------------------------------------------------------
        |
        | This path used for save the generated widget.
        |
         */

        'widgets' => base_path('widgets'),
        /*
        |--------------------------------------------------------------------------
        | Widgets assets path
        |--------------------------------------------------------------------------
        |
         */

        'assets' => public_path('widgets'),
    ],
];
