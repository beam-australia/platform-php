<?php

return [
    'taxonomy' => [
        'name' => 'Categories',
        'slug' => 'categories',
    ],
    'terms' => [
        [
            'name' => 'Science',
            'slug' => 'science',
            'children' => [
                ['name' => 'Space'], 
                ['name' => 'Nature'], 
                ['name' => 'Technology'], 
                ['name' => 'Humans'],
                ['name' => 'Genetics'],
            ],
        ],        
        [
            'name' => 'Sport',
            'slug' => 'sport',
            'children' => [
                ['name' => 'AFL'], 
                ['name' => 'Cricket'], 
                [
                    'name' => 'Rugby',
                    'children' => [
                        ['name' => 'Rugby Union'],
                        ['name' => 'Rugby League'],
                    ],
                ],
            ],
        ],
        [
            'name' => 'Local',
            'children' => [
                ['name' => 'Victoria'],
                ['name' => 'New South Wales'],
                ['name' => 'Queensland'],
                ['name' => 'Western Australia'],
                ['name' => 'Tasmani'],
            ],            
        ],
    ]
];
