<?php

require __DIR__ . '/../vendor/autoload.php';

(new \Zablose\Allog\Server(require_once __DIR__ . '/../.allog.server.config.php'))->run();
