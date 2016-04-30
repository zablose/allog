<?php

namespace Zablose\Allog\Db;

use PDO;
use Exception;
use Zablose\Allog\Db\Tables;

class Allog
{

    const MESSAGE_TYPE_INFO    = 'info';
    const MESSAGE_TYPE_ERROR   = 'error';
    const MESSAGE_TYPE_WARNING = 'warning';

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * Table names to work with.
     *
     * @var \Zablose\Allog\Db\Tables
     */
    protected $table;

    /**
     * Application name the data from.
     *
     * @var string
     */
    protected $appname;

    /**
     * Unique token for the app to access logging server.
     *
     * @var string
     */
    protected $token;

    /**
     * IP address the data from.
     *
     * @var string
     */
    protected $addr;

    /**
     * Create an instance of Allog class with application name, token and IP address.
     *
     * @param string $appname
     * @param string $token
     * @param string $addr
     */
    public function __construct($appname, $token, $addr)
    {
        $this->appname = $appname;
        $this->token   = $token;
        $this->addr    = $addr;

        $this->table = new Tables($this->appname);
        $this->pdo   = new PDO($this->dsn(), ALLOG_DB_USERNAME, ALLOG_DB_PASSWORD);

        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        $this->onErrorsMode();
    }

    /**
     * Set attribute for the PDO to show errors, if 'ALLOG_DB_DEBUG' constant set to TRUE, or forced.
     *
     * @param boolean $force If set to TRUE, errors mode is on, regardless of the constant 'ALLOG_DB_DEBUG' value.
     */
    protected function onErrorsMode($force = false)
    {
        if ($force or ALLOG_DB_DEBUG)
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
        return ALLOG_DB_CONNECTION.':host='.ALLOG_DB_HOST.';port='.ALLOG_DB_PORT
            .';dbname='.ALLOG_DB_DATABASE.';charset='.ALLOG_DB_CHARSET;
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
     * If the table is full, truncate it, and, add a warning message.
     *
     * @param string $table Table name to work with.
     * @param array $attrs Attributes to insert.
     *
     * @return boolean
     */
    protected function forcedInsert($table, $attrs)
    {
        $result = false;

        $this->onErrorsMode(true);

        try
        {
            $result = $this->insert($table, $attrs);
        }
        catch (Exception $e)
        {
            if ((int) $e->getCode() === 22003)
            {
                $this->truncate($table);

                $this->addWarning("ID is out of range for the table '$table'. Table has been truncated!");

                $result = $this->insert($table, $attrs);
            }
            else
            {
                throw $e;
            }
        }

        return $result;
    }

    /**
     * Insert a new row to the table.
     *
     * @param string $table Table name to work with.
     * @param array $attrs Attributes to insert.
     *
     * @return boolean
     */
    protected function insert($table, $attrs)
    {
        $now = date(DATE_ATOM);

        $attrs['created'] = $now;

        if (isset($attrs['updated']))
        {
            $attrs['updated'] = $now;
        }

        $sql = "INSERT INTO $table SET ".$this->set($attrs);

        return $this->pdo->prepare($sql)->execute(array_values($attrs));
    }

    /**
     * Prepare SET string from an array where keys are column names.
     *
     * @param array $attrs
     *
     * @return string
     */
    protected function set($attrs)
    {
        $sets = array_map(function($key)
        {
            return "`$key` = ?";
        }, array_keys($attrs));

        return implode(',', $sets);
    }

    /**
     * Prepare WHERE ??? AND ??? ... string from an array where keys are column names.
     *
     * @param array $attrs
     *
     * @return string
     */
    protected function where($attrs)
    {
        $where = array_map(function($key)
        {
            return "`$key` = ?";
        }, array_keys($attrs));

        return 'WHERE '.implode(' AND ', $where);
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
    public function addMessage($message, $type = self::MESSAGE_TYPE_INFO)
    {
        return $this->forcedInsert($this->table->messages, compact('type', 'message'));
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
    public function getLatestApps($num = 10)
    {
        $sql = "SELECT * FROM {$this->table->apps} ORDER BY created DESC LIMIT $num";

        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute();

        return $pdo_statement->fetchAll();
    }

    /**
     * Get latest N requests.
     *
     * @param integer $num Quantity of the latest requests to grab.
     *
     * @return array
     */
    public function getLatestRequests($num = 10)
    {
        $sql = "SELECT * FROM {$this->table->requests} ORDER BY created DESC LIMIT $num";

        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute();

        return $pdo_statement->fetchAll();
    }

    /**
     * Add a new row to the requests table.
     *
     * @param array $attrs
     *
     * @return boolean
     */
    public function addRequest($attrs)
    {
        return $this->forcedInsert($this->table->requests, $attrs);
    }

    /**
     * Add a new row to the apps table.
     *
     * @param string $appname Application name.
     * @param string $token
     * @param string $addr
     *
     * @return boolean
     */
    public function addApp($appname, $token, $addr)
    {
        $data = [
            'appname'     => $appname,
            'token'       => $token,
            'remote_addr' => $addr,
            'updated'     => true
        ];

        return $this->insert($this->table->apps, $data);
    }

    /**
     * Validate token and IP address of the data sent to application.
     *
     * @return boolean
     */
    public function auth()
    {
        $attrs = [
            'appname'     => $this->appname,
            'token'       => $this->token,
            'remote_addr' => $this->addr,
            'active'      => 1
        ];

        $where = $this->where($attrs);

        $sql = "SELECT `appname` FROM {$this->table->apps} $where LIMIT 1";

        $pdo_statement = $this->pdo->prepare($sql);
        $pdo_statement->execute(array_values($attrs));

        return (empty($pdo_statement->fetchAll())) ? false : true;
    }

}
