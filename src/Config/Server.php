<?php

declare(strict_types=1);

namespace Zablose\Allog\Config;

use Zablose\DotEnv\Env;

class Server extends Config
{
    public string $server_name = '';

    public string $db_connection = '';
    public string $db_host = '';
    public int $db_port = 0;
    public string $db_database = '';
    public string $db_username = '';
    public string $db_password = '';
    public string $db_charset = '';
    public string $db_prefix = '';

    public function read(string $path): self
    {
        parent::read($path);

        $this->server_name = Env::string('ALLOG_SERVER_NAME', 'allog');
        $this->db_connection = Env::string('ALLOG_DB_CONNECTION', 'mysql');
        $this->db_host = Env::string('ALLOG_DB_HOST', 'localhost');
        $this->db_port = Env::int('ALLOG_DB_PORT', 3306);
        $this->db_database = Env::string('ALLOG_DB_DATABASE', 'allog');
        $this->db_username = Env::string('ALLOG_DB_USERNAME', 'allog');
        $this->db_password = Env::string('ALLOG_DB_PASSWORD');
        $this->db_charset = Env::string('ALLOG_DB_CHARSET', 'utf8mb4');
        $this->db_prefix = Env::string('ALLOG_DB_PREFIX');

        return $this;
    }
}
