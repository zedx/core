<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Theme Path
    |--------------------------------------------------------------------------
    |
    | Default theme path.
    |
     */

    'path'      => base_path().'/'.'themes',

    /*
    |--------------------------------------------------------------------------
    | Theme Stubs
    |--------------------------------------------------------------------------
    |
    | Default theme stubs.
    |
     */
    'stubs'     => [
        'path'         => core_src_path().'/Console/Commands/Theme/stubs',
        'files'        => [
            'json' => 'zedx.json',
        ],
        'replacements' => [
            'json' => ['STUDLY_NAME', 'LOWER_NAME'],
        ],
    ],
];
