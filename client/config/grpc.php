<?php

return [
    'services' => [
        'ProtocolBuffer\\Auth\\AuthServiceInterface' => [
            'host' => env('AUTH_SERVICE_HOST'),
            'authentication' => 'insecure', // insecure, tls
            'cert' => env('AUTH_SERVICE_CERT')
        ],
    ],
];
