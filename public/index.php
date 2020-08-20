<?php declare(strict_types=1);

use Zablose\Allog\Config;
use Zablose\Allog\Server;

require __DIR__.'/../vendor/autoload.php';

(new Server(new Config(__DIR__.'/../.env')))->run();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Allog Server</title>
    <link rel="icon" href="data:,">
</head>
</html>
