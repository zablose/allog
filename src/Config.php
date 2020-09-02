<?php declare(strict_types=1);

namespace Zablose\Allog;

class Config
{
    public bool $debug = false;

    public string $server_name = 'allog';

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

    private array $vars = [];

    public function __construct(string $path)
    {
        $this->readConfigFromDotEnvFile($path);
    }

    private function readConfigFromDotEnvFile(string $path): self
    {
        $file = fopen($path, 'r');

        if ($file) {
            while (($line = fgets($file)) !== false) {
                if (strpos($line, '=') === false) {
                    continue;
                }
                [$n, $v] = explode('=', $line);
                $name = strtolower(trim($n));
                $allog_name = str_replace('allog_', '', $name);
                $value = trim(trim($v), '"\'');
                $this->vars[$name] = $this->parseValue($value);
                if (isset($this->{$allog_name})) {
                    $this->{$allog_name} = $this->vars[$name];
                }
                if (strpos($name, 'allog_protected') !== false) {
                    $this->protected[] = $this->vars[$name];
                }
            }

            if (! feof($file)) {
                trigger_error('Allog: Reading of the config file stopped before end of file.', E_USER_WARNING);
            }

            fclose($file);
        }

        return $this;
    }

    private function parseValue(string $value)
    {
        $value = $this->checkValueForVars($value);

        if ($value === 'true') {
            return true;
        }

        if ($value === 'false') {
            return false;
        }

        if (is_numeric($value)) {
            return strpos($value, '.') === false ? intval($value) : floatval($value);
        }

        return $value;
    }

    private function checkValueForVars(string $value)
    {
        $var_start = strpos($value, '${');
        $var_end = strpos($value, '}');
        while ($var_start !== false && $var_end !== false && $var_end > $var_start) {
            $var_name = strtolower(substr($value, $var_start + 2, $var_end - $var_start - 2));
            $var_value = $this->vars[$var_name] ?? 'undefined';
            $value = str_replace('${'.strtoupper($var_name).'}', $var_value, $value);
            $var_start = strpos($value, '${');
            $var_end = strpos($value, '}');
        }

        return $value;
    }
}
