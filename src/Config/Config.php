<?php

declare(strict_types=1);

namespace Zablose\Allog\Config;

use Zablose\DotEnv\Env;

abstract class Config
{
    public bool $debug = false;
    protected Env $env;

    public function __construct()
    {
        $this->env = new Env();
    }

    public function debugOn(): self
    {
        $this->debug = true;

        return $this;
    }

    public function debugOff(): self
    {
        $this->debug = false;

        return $this;
    }

    public function read(string $path): self
    {
        $this->env->read($path);

        $this->debug = Env::bool('ALLOG_DEBUG');

        return $this;
    }
}
