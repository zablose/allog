<?php declare(strict_types=1);

namespace Zablose\Allog;

use Zablose\Allog\Data\Container;

class Server
{
    private Db $db;
    private Container $data;
    private array $config;

    public function __construct(array $config)
    {
        $this->data   = new Container($config);
        $this->db     = new Db($config);
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
                $this->config['server']['name'] ?? 'allog',
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
