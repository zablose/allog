<?php

declare(strict_types=1);

namespace Zablose\Allog;

use Zablose\Allog\Config\Server as Config;

class Table
{
    public const string TABLE_CLIENTS = 'clients';
    public const string TABLE_MESSAGES = 'messages';

    /** Base table name for Allog requests tables. */
    public const string TABLE_REQUESTS = 'requests_';

    private Config $config;
    private string $clients;
    private string $messages;
    private string $requests;

    public function __construct(Config $config)
    {
        $this->config = $config;

        $prefix = $this->config->db_prefix;
        $this->clients = $prefix.static::TABLE_CLIENTS;
        $this->messages = $prefix.static::TABLE_MESSAGES;
        $this->requests = $prefix.static::TABLE_REQUESTS;
    }

    public function clients(): string
    {
        return $this->clients;
    }

    public function messages(): string
    {
        return $this->messages;
    }

    public function requestsServer(): string
    {
        return $this->requests.$this->config->server_name;
    }

    public function requestsClient(string $client_name): string
    {
        return $this->requests.$client_name;
    }
}
