<?php

namespace Zablose\Allog;

class Tables
{

    /**
     * Table name for Allog clients.
     */
    const TABLE_CLIENTS = 'clients';

    /**
     * Table name for Allog messages.
     */
    const TABLE_MESSAGES = 'messages';

    /**
     * Base table name for Allog requests tables.
     */
    const TABLE_REQUESTS = 'requests_';

    /**
     * Full table name for Allog clients.
     *
     * @var string
     */
    private $clients;

    /**
     * Full table name for Allog messages.
     *
     * @var string
     */
    private $messages;

    /**
     * Full table name for Allog requests, based on current client name to log for.
     *
     * @var string
     */
    private $requests;

    /**
     * @param string $prefix Table prefix.
     */
    public function __construct(string $prefix)
    {
        $this->clients  = $prefix . static::TABLE_CLIENTS;
        $this->messages = $prefix . static::TABLE_MESSAGES;
        $this->requests = $prefix . static::TABLE_REQUESTS;
    }

    /**
     * @return string
     */
    public function clients()
    {
        return $this->clients;
    }

    /**
     * @return string
     */
    public function messages()
    {
        return $this->messages;
    }

    /**
     * Get requests table name.
     *
     * @param string $client_name Current client name to log for.
     *
     * @return string
     */
    public function requests($client_name)
    {
        return $this->requests . $client_name;
    }

}
