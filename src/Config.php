<?php declare(strict_types=1);

namespace Zablose\Allog;

use Zablose\DotEnv\Env;

class Config
{
    public bool $debug = false;

    public string $server_name = '';

    public string $db_connection = '';
    public string $db_host = '';
    public int $db_port = 0;
    public string $db_database = '';
    public string $db_username = '';
    public string $db_password = '';
    public string $db_charset = '';
    public string $db_prefix = '';

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
        (new Env())->setArrays(['ALLOG_PROTECTED'])->read($path);

        $this->debug = Env::bool('ALLOG_DEBUG');
        $this->server_name = Env::string('ALLOG_SERVER_NAME', 'allog');
        $this->db_connection = Env::string('ALLOG_DB_CONNECTION', 'mysql');
        $this->db_host = Env::string('ALLOG_DB_HOST', 'localhost');
        $this->db_port = Env::int('ALLOG_DB_PORT', 3306);
        $this->db_database = Env::string('ALLOG_DB_DATABASE', 'allog');
        $this->db_username = Env::string('ALLOG_DB_USERNAME', 'allog');
        $this->db_password = Env::string('ALLOG_DB_PASSWORD');
        $this->db_charset = Env::string('ALLOG_DB_CHARSET', 'utf8mb4');
        $this->db_prefix = Env::string('ALLOG_DB_PREFIX');
        $this->client_state = Env::string('ALLOG_CLIENT_STATE', 'disabled');
        $this->client_name = Env::string('ALLOG_CLIENT_NAME');
        $this->client_token = Env::string('ALLOG_CLIENT_TOKEN');
        $this->server_url = Env::string('ALLOG_SERVER_URL', 'https://www.allog.zdev/');
        $this->protected = Env::array('ALLOG_PROTECTED');

        return $this;
    }
}
