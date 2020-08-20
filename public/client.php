<?php declare(strict_types=1);

use Zablose\Allog\Client;
use Zablose\Allog\Config;

require __DIR__.'/../vendor/autoload.php';

$client = (new Client(new Config(__DIR__.'/../.env')))->send();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Allog Client</title>
    <link rel="icon" href="data:,">
</head>
<body>
<h1>Allog Client</h1>
<h2>Debug</h2>
<pre><?php print_r($client->getError()); ?></pre>
</body>
</html>
