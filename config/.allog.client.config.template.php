<?php

return [

    'client' => [
        /**
         * Values:
         *      'disabled' - Client will not send anything;
         *      'development' - Will send data without proper SSL verification, useful with self-signed certificates;
         *      'production' - Must be used in production.
         */
        'state' => 'disabled',
        'name'  => '',
        'token' => '',
    ],

    'server'    => [
        /**
         * HTTPS only.
         */
        'url' => 'https://allog.server.dev/',
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
