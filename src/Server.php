<?php

namespace Zablose\Allog;

use Zablose\Allog\Data\Container;

class Server
{

    /**
     * Allog DB model instance.
     *
     * @var Db
     */
    private $db;

    /**
     * @var Container
     */
    private $data;

    /**
     * @var array
     */
    private $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->data   = new Container($config);
        $this->db     = new Db($config);
        $this->config = $config;
    }

    /**
     * Save data to the database from the given array or a new data.
     *
     * Truncate table, if ID reached the out of range.
     * Add warning message to the messages table.
     *
     * @return Server
     */
    public function run()
    {
        if ($this->auth())
        {
            $this->db->addRequest(
                $this->data->post->name(),
                $this->data->post->toArray()
            );
        }
        else
        {
            $this->db->addRequest(
                $this->config['server']['name'] ?? 'allog_server',
                $this->data->toArray()
            );
        }

        return $this;
    }

    /**
     * @return bool
     */
    private function auth()
    {
        if (! $this->data->post->name() || ! $this->data->post->token())
        {
            return false;
        }

        return $this->db->auth(
            $this->data->post->name(),
            $this->data->post->token(),
            $this->data->server->remote_addr
        );
    }

}
