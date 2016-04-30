<?php

namespace Zablose\Allog;

use Zablose\Allog\Db\Allog;
use Zablose\Allog\Data\Data;

class Server
{

    /**
     * Allog DB model instance.
     *
     * @var \Zablose\Allog\Db\Allog
     */
    protected $db;

    /**
     * Server side of Allog.
     *
     * @param string $appname Application name logs are from.
     * @param string $token
     * @param string $addr
     */
    public function __construct($appname, $token = null, $addr = null)
    {
        $this->db = new Allog($appname, $token, $addr);
    }

    /**
     * Get latest N logs.
     *
     * @param integer $num Quantity of the latest logs to grab.
     *
     * @return array
     */
    public function logs($num = 10)
    {
        return $this->db->getLatestRequests($num);
    }

    /**
     * Save data to the database from the given array or a new data.
     *
     * Truncate table, if ID reached the out of range.
     * Add warning message to the messages table.
     *
     * @param array $data
     *
     * @return \Zablose\Allog\Server
     */
    public function save($data = null)
    {
        $data = (new Data())->toArray($data);

        $this->db->addRequest($data);

        return $this;
    }

    /**
     * Validate token and IP address of the application in the database.
     *
     * @return boolean
     */
    public function auth()
    {
        return $this->db->auth();
    }

    /**
     * Add a new application to the database.
     *
     * @param string $appname Application name.
     * @param string $token
     * @param string $addr
     *
     * @return \Zablose\Allog\Server
     */
    public function addApp($appname, $token, $addr)
    {
        $this->db->addApp($appname, $token, $addr);

        return $this;
    }

    /**
     * Get latest N applications.
     *
     * @param integer $num
     *
     * @return array
     */
    public function apps($num = 10)
    {
        return $this->db->getLatestApps($num);
    }

    /**
     * Add a new info message to the messages table.
     *
     * @param string $message
     *
     * @return boolean
     */
    public function info($message)
    {
        return $this->db->addInfo($message);
    }

}
