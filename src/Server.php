<?php

declare(strict_types=1);

namespace Zablose\Allog;

use Exception;
use Zablose\Allog\Data\Container;
use Zablose\Allog\Config\Server as Config;

class Server
{
    private Config $config;
    private Container $data;
    private Db $db;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->data = new Container();
        $this->db = new Db($config);
    }

    /**
     * @throws Exception
     */
    public function run(): self
    {
        if ($this->auth()) {
            $this->db->addRequest(
                $this->data->post()->getAllogClientName(),
                $this->data->post()->toArray()
            );
        } else {
            $this->db->addRequest(
                $this->config->server_name,
                $this->data->toArray()
            );
        }

        return $this;
    }

    private function auth(): bool
    {
        if (! $this->data->post()->getAllogClientName() || ! $this->data->post()->getAllogClientToken()) {
            return false;
        }

        return $this->db->auth(
            $this->data->post()->getAllogClientName(),
            $this->data->post()->getAllogClientToken(),
        );
    }
}
