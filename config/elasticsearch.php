<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Connection settings
    |--------------------------------------------------------------------------
    |
    | Configure the conenction to elasticsearch service
    |
    */    

    'default' => 'default',
    
    'connections' => [
        'default' => [
            'hosts'             => [ env('ES_HOST', 'localhost:9200') ],
            'retries'           => 1,
            'sslVerification'   => null,
            'logging'           => false,
        ],
    ],    

    /*
    |--------------------------------------------------------------------------
    | Index name
    |--------------------------------------------------------------------------
    |
    | Name of the primary Elasticsearch index.
    | At present the package only supports a single index.
    |
    */
   
	'index'	=> env('ES_INDEX', 'my-index'),

    /*
    |--------------------------------------------------------------------------
    | Index settings
    |--------------------------------------------------------------------------
    |
    | The index settings initialised at creation time
    |
    */
   
	'settings' => [
        'index' => [
            'number_of_shards' => 3, 
            'number_of_replicas' => 2, 
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Index mappings and defaults
    |--------------------------------------------------------------------------
    |
    | Set any predefined or default index document mappings
    |
    */

	'mappings'	=> [],

    /*
    |--------------------------------------------------------------------------
    | Indexable models
    |--------------------------------------------------------------------------
    |
    | An array of indexable models, must implment the Indexable interface
    |
    */    
   
   'indexables' => [
        // App\User::class,
   ],
];
