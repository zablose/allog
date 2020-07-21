<?php declare(strict_types=1);

namespace Zablose\Allog;

class Tables
{
    const TABLE_CLIENTS  = 'clients';
    const TABLE_MESSAGES = 'messages';

    /** Base table name for Allog requests tables. */
    const TABLE_REQUESTS = 'requests_';

    private string $clients;
    private string $messages;
    private string $requests;

    public function __construct(string $prefix)
    {
        $this->clients  = $prefix.static::TABLE_CLIENTS;
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

    public function requests(string $client_name): string
    {
        return $this->requests.$client_name;
    }
}
