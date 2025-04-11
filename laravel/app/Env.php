<?php

declare(strict_types=1);

namespace App;

class Env
{
    public static function path(): string
    {
        return dirname(__DIR__, 2) . '/.env';
    }
}
