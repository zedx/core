<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Default module namespace.
    |
     */

    'namespace' => 'ZEDx\Modules',

    /*
    |--------------------------------------------------------------------------
    | Module Stubs
    |--------------------------------------------------------------------------
    |
    | Default module stubs.
    |
     */

    'stubs' => [
        'path'  => core_src_path().'/Console/Commands/Module/stubs',
        'files' => [
            'start'           => 'start.php',
            'routes'          => 'Http/routes.php',
            'json'            => 'zedx.json',
            'views/index'     => 'Resources/views/index.blade.php',
            'views/master'    => 'Resources/views/layouts/master.blade.php',
            'scaffold/config' => 'Config/config.php',
            'scaffold/module' => 'Module.php',
            'composer'        => 'composer.json',
        ],
        'replacements' => [
            'start'           => ['LOWER_NAME'],
            'routes'          => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE'],
            'json'            => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE'],
            'views/index'     => ['LOWER_NAME'],
            'views/master'    => ['STUDLY_NAME'],
            'scaffold/config' => ['STUDLY_NAME'],
            'scaffold/module' => ['LOWER_NAME', 'STUDLY_NAME'],
            'composer'        => [
                'LOWER_NAME',
                'STUDLY_NAME',
                'VENDOR',
                'AUTHOR_NAME',
                'AUTHOR_EMAIL',
                'MODULE_NAMESPACE',
            ],
        ],
    ],
    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Modules path
        |--------------------------------------------------------------------------
        |
        | This path used for save the generated module.
        |
         */

        'modules' => base_path('modules'),
        /*
        |--------------------------------------------------------------------------
        | Modules assets path
        |--------------------------------------------------------------------------
        |
         */

        'assets' => public_path('modules'),
        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        |
         */

        'generator' => [
            'distAssets' => 'Resources/assets/dist',
            'srcAssets'  => 'Resources/assets/src',
            'config'     => 'Config',
            'command'    => 'Console',
            'migration'  => 'Database/Migrations',
            'model'      => 'Models',
            'repository' => 'Repositories',
            'seeder'     => 'Database/Seeders',
            'controller' => 'Http/Controllers',
            'filter'     => 'Http/Middleware',
            'request'    => 'Http/Requests',
            'provider'   => 'Providers',
            'lang'       => 'Resources/lang',
            'views'      => 'Resources/views',
            'test'       => 'Tests',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Composer File Template
    |--------------------------------------------------------------------------
    |
     */

    'composer' => [
        'vendor' => 'ZEDx',
        'author' => [
            'name'  => 'Amine OUDJEHIH',
            'email' => 'amine.oudjehih@gmail.com',
        ],
    ],
];
