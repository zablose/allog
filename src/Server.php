<?php declare(strict_types=1);

namespace Zablose\Allog;

use Zablose\Allog\Data\Container;

class Server
{
    private Config $config;
    private Container $data;
    private Db $db;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->data = new Container($config);
        $this->db = new Db($config);
    }

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
        if ($this->data->server()->remote_addr === '127.0.0.1') {
            return (bool) $this->data->post()->getAllogClientName();
        }

        if (! $this->data->post()->getAllogClientName() || ! $this->data->post()->getAllogClientToken()) {
            return false;
        }

        return $this->db->auth(
            $this->data->post()->getAllogClientName(),
            $this->data->post()->getAllogClientToken(),
            $this->data->server()->remote_addr
        );
    }
}
