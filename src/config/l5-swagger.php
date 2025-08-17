<?php

return [
    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'Catalog API',
            ],
            'routes' => [
                'api' => 'api/documentation',
                'docs' => 'docs',
//                 'docs' => 'api/documentation',

                'middleware' => [],
                'group_options' => [],
            ],
            'paths' => [
                'use_absolute_path' => false,
                'docs_json' => 'api-docs.json',
                'format_to_use_for_docs' => 'json',
                'annotations' => [ base_path('app') ],
                'excludes' => [],
            ],
        ],
    ],
];

