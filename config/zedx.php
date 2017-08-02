<?php

return [
    'images' => [
        'thumb' => [
            'watermark'    => env('ZEDX_IMAGES_THUMB_WATERMARK', false),
            'resizeCanvas' => env('ZEDX_IMAGES_THUMB_RESIZECANVAS', true),
            'colorCanvas'  => env('ZEDX_IMAGES_THUMB_COLORCANVAS', 'ffffff'),
            'path'         => 'uploads/thumbs',
            'size'         => [
                'width'  => env('ZEDX_IMAGES_THUMB_SIZE_WIDTH', 76),
                'height' => env('ZEDX_IMAGES_THUMB_SIZE_HEIGHT', 76),
            ],
        ],
        'medium' => [
            'watermark'    => env('ZEDX_IMAGES_MEDIUM_WATERMARK', false),
            'resizeCanvas' => env('ZEDX_IMAGES_MEDIUM_RESIZECANVAS', true),
            'colorCanvas'  => env('ZEDX_IMAGES_MEDIUM_COLORCANVAS', 'ffffff'),
            'path'         => 'uploads/mediums',
            'size'         => [
                'width'  => env('ZEDX_IMAGES_MEDIUM_SIZE_WIDTH', 162),
                'height' => env('ZEDX_IMAGES_MEDIUM_SIZE_HEIGHT', 100),
            ],
        ],
        'large' => [
            'watermark'    => env('ZEDX_IMAGES_LARGE_WATERMARK', true),
            'resizeCanvas' => env('ZEDX_IMAGES_LARGE_RESIZECANVAS', true),
            'colorCanvas'  => env('ZEDX_IMAGES_LARGE_COLORCANVAS', 'ffffff'),
            'path'         => 'uploads/larges',
            'size'         => [
                'width'  => env('ZEDX_IMAGES_LARGE_SIZE_WIDTH', 605),
                'height' => env('ZEDX_IMAGES_LARGE_SIZE_HEIGHT', 403),
            ],
        ],
        'avatar' => [
            'resizeCanvas' => true,
            'colorCanvas'  => 'ffffff',
            'path'         => 'uploads/avatars',
            'size'         => [
                'width'  => 100,
                'height' => 100,
            ],
        ],
        'category' => [
            'resizeCanvas' => true,
            'colorCanvas'  => 'ffffff',
            'path'         => 'uploads/categories',
            'size'         => [
                'width'  => 100,
                'height' => 100,
            ],
        ],
    ],
    'watermark' => [
        'path'     => 'uploads/watermark.png',
        'position' => env('ZEDX_IMAGES_WATERMARK_POSITION', 'top-left'),
        'size'     => [
            'width'  => env('ZEDX_IMAGES_WATERMARK_SIZE_WIDTH', 50),
            'height' => env('ZEDX_IMAGES_WATERMARK_SIZE_HEIGHT', 50),
        ],
    ],
];
