<?php

use Zablose\Allog\Db;

$now = date(Db::DATE_FORMAT);

return [
    [
        'name' => env('ALLOG_CLIENT_NAME'),
        'token' => env('ALLOG_CLIENT_TOKEN'),
        'remote_addr' => env('ALLOG_CLIENT_IP'),
        'active' => 1,
        'updated' => $now,
        'created' => $now,
    ],
    [
        'name' => env('ALLOG_CLIENT_1_NAME'),
        'token' => env('ALLOG_CLIENT_1_TOKEN'),
        'remote_addr' => env('ALLOG_CLIENT_1_IP'),
        'active' => 1,
        'updated' => $now,
        'created' => $now,
    ],
];
