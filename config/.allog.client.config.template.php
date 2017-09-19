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

    'server' => [
        /**
         * HTTPS only.
         */
        'url' => 'https://allog.server.dev/',
    ],

    'protected' => [
        'password',
        'password_confirmation',
    ],

];
