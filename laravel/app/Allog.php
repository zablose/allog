<?php

declare(strict_types=1);

namespace App;

use Zablose\Allog\Client;
use Zablose\Allog\Config\Client as ClientConfig;
use Zablose\Allog\Config\Server as ServerConfig;
use Zablose\Allog\Server;
use Zablose\Allog\Table;

class Allog
{
    public static function table(): Table
    {
        return new Table((new ServerConfig())->read(Env::path()));
    }

    public static function client(): Client
    {
        return (new Client((new ClientConfig())->read(Env::path())));
    }

    public static function server(): Server
    {
        return (new Server((new ServerConfig())->read(Env::path())));
    }
}
