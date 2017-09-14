<?php

namespace Zablose\Allog;

use PDO;
use Exception;

class Db
{

    const MESSAGE_TYPE_INFO    = 'info';
    const MESSAGE_TYPE_ERROR   = 'error';
    const MESSAGE_TYPE_WARNING = 'warning';

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * Table names to work with.
     *
     * @var Tables
     */
    private $tables;

    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        $this->tables = new Tables($config['db']['prefix'] ?? '');

        $this->pdo = new PDO(
            $this->dsn(),
            $config['db']['username'] ?? '',
            $config['db']['password'] ?? ''
        );

        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        $this->throwExceptions($this->config['db']['debug'] ?? false);
    }

    /**
     * Set attribute for the PDO to show errors.
     *
     * @param boolean $yes
     */
    protected function throwExceptions($yes)
    {
        if ($yes)
        {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    /**
     * Form DSN string.
     *
     * @return string
     */
    protected function dsn()
    {
        return ($this->config['db']['connection'] ?? 'mysql') .
            ':host=' . ($this->config['db']['host'] ?? 'localhost') .
            ';port=' . ($this->config['db']['port'] ?? '3306') .
            ';dbname=' . ($this->config['db']['database'] ?? 'allog') .
            ';charset=' . ($this->config['db']['charset'] ?? 'utf8mb4');
    }

    /**
     * Truncate table.
     *
     * @param string $table
     *
     * @return boolean
     */
    protected function truncate($table)
    {
        return (boolean) $this->pdo->exec("TRUNCATE TABLE `$table`");
    }

    /**
     * Insert a new row to the table.
     * If it is full, truncate it, and, add a warning message.
     *
     * @param string $table  Table name to work with.
     * @param array  $fields Table fields to fill.
     *
     * @return bool
     *
     * @throws Exception
     */
    protected function forcedInsert($table, $fields)
    {
        $this->throwExceptions(true);

        try
        {
            $result = $this->insert($table, $fields);
        }
        catch (Exception $exception)
        {
            if ((int) $exception->getCode() === 22003)
            {
                $this->truncate($table);

                $this->addWarning("ID is out of range for the table '$table'. Table has been truncated!");

                $result = $this->insert($table, $fields);
            }
            else
            {
                throw $exception;
            }
        }

        return $result;
    }

    /**
     * Insert a new row to the table.
     *
     * @param string $table  Table name to work with.
     * @param array  $fields Table fields to fill.
     *
     * @return boolean
     */
    protected function insert($table, $fields)
    {
        $now = date(DATE_ATOM);

        $fields['created'] = $now;

        if (isset($fields['updated']))
        {
            $fields['updated'] = $now;
        }

        $sql = "INSERT INTO `$table` SET " . $this->set($fields);

        return $this->pdo->prepare($sql)->execute(array_values($fields));
    }

    /**
     * Prepare SET string from an array where keys are column names.
     *
     * @param array $fields
     *
     * @return string
     */
    protected function set($fields)
    {
        return implode(',', $this->prepared($fields));
    }

    /**
     * Prepare WHERE ??? AND ??? ... string from an array where keys are column names.
     *
     * @param array $fields
     *
     * @return string
     */
    protected function where($fields)
    {
        return 'WHERE ' . implode(' AND ', $this->prepared($fields));
    }

    /**
     * @param array $fields
     *
     * @return array
     */
    protected function prepared($fields)
    {
        return array_map(function ($key)
        {
            return "`$key` = ?";
        }, array_keys($fields));
    }

    /**
     * Add a new row to the messages table.
     * If the table is full, truncate it, and, add a warning message.
     *
     * @param string $message
     * @param string $type
     *
     * @return boolean
     */
    protected function addMessage($message, $type = self::MESSAGE_TYPE_INFO)
    {
        return $this->forcedInsert($this->tables->messages(), compact('type', 'message'));
    }

    /**
     * Add a new info message to the messages table.
     * If the table is full, truncate it, and, add a warning message.
     *
     * @param string $message
     *
     * @return boolean
     */
    public function addInfo($message)
    {
        return $this->addMessage($message);
    }

    /**
     * Add a new warning message to the messages table.
     * If the table is full, truncate it, and, add a warning message.
     *
     * @param string $message
     *
     * @return boolean
     */
    public function addWarning($message)
    {
        return $this->addMessage($message, static::MESSAGE_TYPE_WARNING);
    }

    /**
     * Add a new error message to the messages table.
     * If the table is full, truncate it, and, add a warning message.
     *
     * @param string $message
     *
     * @return boolean
     */
    public function addError($message)
    {
        return $this->addMessage($message, static::MESSAGE_TYPE_ERROR);
    }

    /**
     * Get latest N applications.
     *
     * @param integer $num
     *
     * @return array
     */
    public function getLatestClients($num = 10)
    {
        $sql           = "SELECT * FROM `{$this->tables->clients()}` ORDER BY created DESC LIMIT $num";
        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute();

        return $pdo_statement->fetchAll();
    }

    /**
     * Get latest N requests.
     *
     * @param string  $client_name
     * @param integer $num
     *
     * @return array
     */
    public function getLatestRequests($client_name, $num = 10)
    {
        $table         = $this->tables->requests($client_name);
        $sql           = "SELECT * FROM `$table` ORDER BY created DESC LIMIT $num";
        $pdo_statement = $this->pdo->prepare($sql);

        $pdo_statement->execute();

        return $pdo_statement->fetchAll();
    }

    /**
     * Add a new row to the requests table.
     *
     * @param string $client_name
     * @param array  $fields
     *
     * @return bool
     */
    public function addRequest($client_name, $fields)
    {
        return $this->forcedInsert($this->tables->requests($client_name), $fields);
    }

    /**
     * Add a new row to the clients table.
     *
     * @param string $name
     * @param string $token
     * @param string $ip
     *
     * @return boolean
     */
    public function addClient($name, $token, $ip)
    {
        return $this->insert(
            $this->tables->clients(),
            [
                'name'        => $name,
                'token'       => $token,
                'remote_addr' => $ip,
                'updated'     => true,
            ]
        );
    }

    /**
     * Validate client name, token and IP address of the data, sent to the Allog server.
     *
     * @param string $client_name
     * @param string $token
     * @param string $ip
     *
     * @return bool
     */
    public function auth($client_name, $token, $ip)
    {
        $fields = [
            'name'        => $client_name,
            'token'       => $token,
            'remote_addr' => $ip,
            'active'      => 1,
        ];

        $sql = "SELECT `name` FROM `{$this->tables->clients()}` {$this->where($fields)} LIMIT 1";

        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute(array_values($fields));

        return ! empty($pdo_statement->fetchAll());
    }

}
