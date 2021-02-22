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

        $this->debug = (bool) Env::get('ALLOG_DEBUG', false);
        $this->server_name = (string) Env::get('ALLOG_SERVER_NAME', 'allog');
        $this->db_connection = (string) Env::get('ALLOG_DB_CONNECTION', 'mysql');
        $this->db_host = (string) Env::get('ALLOG_DB_HOST', 'localhost');
        $this->db_port = (int) Env::get('ALLOG_DB_PORT', 3306);
        $this->db_database = (string) Env::get('ALLOG_DB_DATABASE', 'allog');
        $this->db_username = (string) Env::get('ALLOG_DB_USERNAME', 'allog');
        $this->db_password = (string) Env::get('ALLOG_DB_PASSWORD', '');
        $this->db_charset = (string) Env::get('ALLOG_DB_CHARSET', 'utf8mb4');
        $this->db_prefix = (string) Env::get('ALLOG_DB_PREFIX', '');
        $this->client_state = (string) Env::get('ALLOG_CLIENT_STATE', 'disabled');
        $this->client_name = (string) Env::get('ALLOG_CLIENT_NAME', '');
        $this->client_token = (string) Env::get('ALLOG_CLIENT_TOKEN', '');
        $this->server_url = (string) Env::get('ALLOG_SERVER_URL', 'https://www.allog.zdev/');
        $this->protected = (array) Env::get('ALLOG_PROTECTED', []);

        return $this;
    }
}
