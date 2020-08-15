<?php declare(strict_types=1);

namespace Zablose\Allog;

class Config
{
    public string $server_name = 'allog';
    public string $server_timezone = 'Europe/London';

    public bool $db_debug = false;
    public string $db_connection = 'mysql';
    public string $db_host = 'localhost';
    public int $db_port = 3306;
    public string $db_database = 'allog';
    public string $db_username = 'allog';
    public string $db_password = '';
    public string $db_charset = 'utf8mb4';
    public string $db_prefix = '';

    /**
     * Values:
     *      'disabled' - Do not send anything;
     *      'development' - Send data without proper SSL verification, useful with self-signed certificates;
     *      'production' - Send data with proper SSL verification;
     *      'local' - Send data using HTTP, may be used when client and server is the same thing.
     */
    public string $client_state = 'disabled';
    public string $client_name = '';
    public string $client_token = '';

    public string $server_url = 'https://www.allog.zdev/';

    /**
     * Keys in data array, which values to be replaced with '*'.
     * Applies for POST only.
     */
    public array $protected = [];

    public function __construct(string $path)
    {
        $this->readConfigFromDotEnvFile($path);
    }

    private function readConfigFromDotEnvFile(string $path): self
    {
        $file = fopen($path, 'r');

        if ($file) {
            while (($line = fgets($file)) !== false) {
                if (stripos($line, 'allog_') === false) {
                    continue;
                }
                [$n, $v] = explode('=', $line);
                $name  = str_replace('allog_', '', strtolower(trim($n)));
                $value = trim($v);
                if (isset($this->{$name})) {
                    switch ($value) {
                        case 'true':
                            $this->{$name} = true;
                            break;
                        case 'false':
                            $this->{$name} = false;
                            break;
                        case is_numeric($value):
                            $this->{$name} = intval($value);
                            break;
                        default:
                            $this->{$name} = $value;
                    }
                    continue;
                }
                if (strpos($name, 'protected') !== false) {
                    $this->protected[] = substr($name, 10);
                }
            }

            if (! feof($file)) {
                trigger_error('Allog: Reading of the config file stopped before end of file.', E_USER_WARNING);
            }

            fclose($file);
        }

        return $this;
    }
}
