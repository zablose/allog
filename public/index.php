<?php declare(strict_types=1);

use Zablose\Allog\Server;

require __DIR__.'/../vendor/autoload.php';

(new Server(require_once __DIR__.'/../.allog.server.config.php'))->run();
