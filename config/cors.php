<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel CORS
     |--------------------------------------------------------------------------
     |

     | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
     | to accept any value.
     |
     */
    'supportsCredentials' => false,
    'allowedOrigins' => ['*'],//*/ ['https://laravel.com'],
    'allowedHeaders' => ['*'],
    'allowedMethods' => ['GET'],
    'exposedHeaders' => [],
    'maxAge' => 0,
    'hosts' => [],
];

