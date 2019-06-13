<?php

return [
    'taxonomy' => [
        'name' => 'Genres',
    ],
    'terms' => [
        [
            'name' => 'Action',
            'children' => [
                ['name' => 'Action 1'],
                ['name' => 'Action 2'],
                ['name' => 'Action 3'],
            ],
        ],
        [
            'name' => 'Romance',
            'children' => [
                ['name' => 'Romance 1'],
                ['name' => 'Romance 2'],
                ['name' => 'Romance 3'],
            ],
        ],
        [
            'name' => 'Comedy',
            'children' => [
                ['name' => 'Comedy 1'],
                ['name' => 'Comedy 2'],
                ['name' => 'Comedy 3'],
            ],
        ],
    ]
];
