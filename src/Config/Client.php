<?php

declare(strict_types=1);

namespace Zablose\Allog\Config;

use Zablose\DotEnv\Env;

class Client extends Config
{
    /**
     * Values:
     *      'disabled' - Do not send anything;
     *      'development' - Send data without proper SSL verification, useful with self-signed certificates;
     *      'production' - Send data with proper SSL verification;
     *      'local' - Send data using HTTP, may be used when client and server is the same thing.
     */
    public string $client_state = '';
    public string $client_name = '';
    public string $client_token = '';

    public string $server_url = '';

    /**
     * Keys in data array, which values to be replaced with '*'.
     * Applies for POST only.
     */
    public array $protected = [];

    public function __construct()
    {
        parent::__construct();
        $this->env->setArrays(['ALLOG_PROTECTED']);
    }

    public function read(string $path): self
    {
        parent::read($path);

        $this->client_state = Env::string('ALLOG_CLIENT_STATE', 'disabled');
        $this->client_name = Env::string('ALLOG_CLIENT_NAME');
        $this->client_token = Env::string('ALLOG_CLIENT_TOKEN');
        $this->server_url = Env::string('ALLOG_SERVER_URL', 'https://www.allog.zdev/');
        $this->protected = Env::array('ALLOG_PROTECTED');

        return $this;
    }
}
