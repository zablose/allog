<?php declare(strict_types=1);

namespace Zablose\Allog;

use PDO;
use Exception;

class Db
{
    const DATE_FORMAT = 'Y-m-d H:i:s';
    const MESSAGE_TYPE_ERROR = 'error';
    const MESSAGE_TYPE_INFO = 'info';
    const MESSAGE_TYPE_WARNING = 'warning';

    private PDO $pdo;
    private Table $table;
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;

        $this->table = new Table($config->db_prefix);

        $this->pdo = new PDO(
            $this->formDsnString(),
            $config->db_username,
            $config->db_password,
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_PERSISTENT => true,
            ]
        );

        $this->throwExceptions($config->debug);
    }

    protected function throwExceptions(bool $yes): void
    {
        if ($yes) {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    protected function formDsnString(): string
    {
        return $this->config->db_connection.
            ':host='.$this->config->db_host.
            ';port='.$this->config->db_port.
            ';dbname='.$this->config->db_database.
            ';charset='.$this->config->db_charset;
    }

    protected function truncate(string $table): bool
    {
        return (boolean) $this->pdo->exec("TRUNCATE TABLE `$table`");
    }

    /**
     * Insert a new row to the table.
     * If it is full, truncate it, and, add a warning message.
     *
     * @param  string  $table   Table name to work with.
     * @param  array   $fields  Table fields to fill.
     *
     * @return bool
     *
     * @throws Exception
     */
    protected function forcedInsert(string $table, array $fields): bool
    {
        $this->throwExceptions(true);

        try {
            $result = $this->insert($table, $fields);
        } catch (Exception $exception) {
            if ((int) $exception->getCode() === 22003) {
                $this->truncate($table);

                $this->addWarning("ID is out of range for the table '$table'. Table was truncated!");

                $result = $this->insert($table, $fields);
            } else {
                throw $exception;
            }
        }

        return $result;
    }

    /**
     * Insert a new row to the table.
     *
     * @param  string  $table   Table name to work with.
     * @param  array   $fields  Table fields to fill.
     *
     * @return bool
     */
    protected function insert(string $table, array $fields): bool
    {
        $now = date(self::DATE_FORMAT);

        $fields['created'] = $now;

        if (isset($fields['updated'])) {
            $fields['updated'] = $now;
        }

        $sql = "INSERT INTO `$table` SET ".$this->set($fields);

        return $this->pdo->prepare($sql)->execute(array_values($fields));
    }

    /**
     * Prepare SET string from an array where keys are column names.
     *
     * @param  array  $fields
     *
     * @return string
     */
    protected function set(array $fields): string
    {
        return implode(',', $this->prepared($fields));
    }

    /**
     * Prepare WHERE ??? AND ??? ... string from an array where keys are column names.
     *
     * @param  array  $fields
     *
     * @return string
     */
    protected function where(array $fields): string
    {
        return 'WHERE '.implode(' AND ', $this->prepared($fields));
    }

    protected function prepared(array $fields): array
    {
        return array_map(fn($key) => "`$key` = ?", array_keys($fields));
    }

    /**
     * Add a new row to the messages table.
     * If the table is full, truncate it, and, add a warning message.
     *
     * @param  string  $message
     * @param  string  $type
     *
     * @return bool
     *
     * @throws Exception
     */
    protected function addMessage(string $message, string $type = self::MESSAGE_TYPE_INFO): bool
    {
        return $this->forcedInsert($this->table->messages(), compact('type', 'message'));
    }

    /**
     * Add a new info message to the messages table.
     * If the table is full, truncate it, and, add a warning message.
     *
     * @param  string  $message
     *
     * @return bool
     *
     * @throws Exception
     */
    public function addInfo(string $message): bool
    {
        return $this->addMessage($message);
    }

    /**
     * Add a new warning message to the messages table.
     * If the table is full, truncate it, and, add a warning message.
     *
     * @param  string  $message
     *
     * @return bool
     *
     * @throws Exception
     */
    public function addWarning(string $message): bool
    {
        return $this->addMessage($message, static::MESSAGE_TYPE_WARNING);
    }

    /**
     * Add a new error message to the messages table.
     * If the table is full, truncate it, and, add a warning message.
     *
     * @param  string  $message
     *
     * @return boolean
     *
     * @throws Exception
     */
    public function addError(string $message): bool
    {
        return $this->addMessage($message, static::MESSAGE_TYPE_ERROR);
    }

    public function getLatestClients(int $num = 10): array
    {
        $sql = "SELECT * FROM `{$this->table->clients()}` ORDER BY created DESC LIMIT $num";
        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute();

        return $pdo_statement->fetchAll();
    }

    public function getLatestRequests(string $client_name, int $num = 10): array
    {
        $table = $this->table->requests($client_name);
        $sql = "SELECT * FROM `$table` ORDER BY created DESC LIMIT $num";
        $pdo_statement = $this->pdo->prepare($sql);

        $pdo_statement->execute();

        return $pdo_statement->fetchAll();
    }

    public function addRequest(string $client_name, array $fields): bool
    {
        return $this->forcedInsert($this->table->requests($client_name), $fields);
    }

    public function addClient(string $name, string $token, string $ip): bool
    {
        return $this->insert(
            $this->table->clients(),
            [
                'name' => $name,
                'token' => $token,
                'remote_addr' => $ip,
                'updated' => true,
            ]
        );
    }

    public function auth(string $client_name, string $token, string $ip): bool
    {
        $fields = [
            'name' => $client_name,
            'token' => $token,
            'remote_addr' => $ip,
            'active' => 1,
        ];

        $sql = "SELECT `name` FROM `{$this->table->clients()}` {$this->where($fields)} LIMIT 1";

        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute(array_values($fields));

        return ! empty($pdo_statement->fetchAll());
    }
}
