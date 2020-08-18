<?php declare(strict_types=1);

namespace Zablose\Allog;

use Zablose\Allog\Data\Container;

class Server
{
    private Db $db;
    private Container $data;
    private Config $config;

    public function __construct(Config $config)
    {
        $this->data = new Container($config);
        $this->db = new Db($config);
        $this->config = $config;
    }

    public function run(): self
    {
        if ($this->auth()) {
            $this->db->addRequest(
                $this->data->post()->name(),
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
        if ($this->data->server()->remote_addr === '127.0.0.1') {
            return (bool) $this->data->post()->name();
        }

        if (! $this->data->post()->name() || ! $this->data->post()->token()) {
            return false;
        }

        return $this->db->auth(
            $this->data->post()->name(),
            $this->data->post()->token(),
            $this->data->server()->remote_addr
        );
    }
}
