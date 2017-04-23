<?php

return [
    'images' => [
        'thumb' => [
            'watermark'    => false,
            'resizeCanvas' => true,
            'colorCanvas'  => 'fffff',
            'path'         => 'uploads/thumbs',
            'size'         => [
                'width'  => 76,
                'height' => 76,
            ],
        ],
        'medium' => [
            'watermark'    => false,
            'resizeCanvas' => true,
            'colorCanvas'  => 'fffff',
            'path'         => 'uploads/mediums',
            'size'         => [
                'width'  => 162,
                'height' => 100,
            ],
        ],
        'large' => [
            'watermark'    => true,
            'resizeCanvas' => true,
            'colorCanvas'  => 'fffff',
            'path'         => 'uploads/larges',
            'size'         => [
                'width'  => 605,
                'height' => 403,
            ],
        ],
        'avatar' => [
            'resizeCanvas' => true,
            'colorCanvas'  => 'fffff',
            'path'         => 'uploads/avatars',
            'size'         => [
                'width'  => 100,
                'height' => 100,
            ],
        ],
        'category' => [
            'resizeCanvas' => true,
            'colorCanvas'  => 'fffff',
            'path'         => 'uploads/categories',
            'size'         => [
                'width'  => 100,
                'height' => 100,
            ],
        ],
    ],
    'watermark' => [
        'path'     => 'uploads/watermark.png',
        'position' => 'top-left',
        'size'     => [
            'width'  => 50,
            'height' => 50,
        ],
    ],
];
