<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

    // 1. Specify paths where CORS is enabled.
    // We keep api/* and add login if it is defined outside the api group.
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login'],

    'allowed_methods' => ['*'],

    // 2. SECURITY: Specify exact origins instead of using '*'.
    // Add your server IP, local hosts, and frontend URLs here.
    'allowed_origins' => [
        'http://172.30.10.12:7443',
        'http://localhost:7443',
        'http://172.30.10.12:9443',
        'http://localhost:5173',
        'http://172.30.10.12:5173',
        'http://localhost',
        'http://127.0.0.1',
    ],

    'allowed_origins_patterns' => [],

    // 3. Allow headers.
    // Critical for API: Content-Type, X-Requested-With, Authorization, Accept.
    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // 4. CRITICAL POINT:
    // Set this to true if you are using Sanctum or plan to use session-based cookies.
    // IMPORTANT: If this is true, '*' CANNOT be used in 'allowed_origins'.
    'supports_credentials' => true,

];
