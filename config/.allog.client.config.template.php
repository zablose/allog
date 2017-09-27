<?php

return [
    'client'    => [
        /**
         * Values:
         *      'disabled' - Do not send anything;
         *      'development' - Send data without proper SSL verification, useful with self-signed certificates;
         *      'production' - Send data with proper SSL verification;
         *      'local' - Send data using HTTP, may be used when client and server is the same thing.
         */
        'state' => 'disabled',
        'name'  => '',
        'token' => '',
    ],
    'server'    => [
        'url' => 'http://allog.server.dev/',
    ],
    /**
     * Keys in data array, which values to be replaced with '*'.
     * Applies for POST only.
     */
    'protected' => [
        '_token',
        'password',
        'password_confirmation',
    ],
];
