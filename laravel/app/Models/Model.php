<?php

declare(strict_types=1);

namespace App\Models;

use Zablose\Allog\Config\Server as Config;
use Zablose\Allog\Table;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
    private static ?Config $config = null;
    private static ?Table $allog_table = null;

    protected function envpath(): string
    {
        return dirname(__DIR__, 3) . '/.env';
    }

    protected function config(): Config
    {
        if (is_null(self::$config)) {
            self::$config = (new Config())->read($this->envpath());
        }

        return self::$config;
    }

    protected function table(): Table
    {
        if (is_null(self::$allog_table)) {
            self::$allog_table = new Table($this->config());
        }

        return self::$allog_table;
    }
}
