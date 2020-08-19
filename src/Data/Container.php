<?php declare(strict_types=1);

namespace Zablose\Allog\Data;

use Zablose\Allog\Config;

/**
 * Container class for storing Server, Post and Get data in one place.
 */
class Container
{
    private Server $server;
    private Post $post;
    private Get $get;

    public function __construct(Config $config)
    {
        $this->server = new Server();
        $this->post = new Post($config);
        $this->get = new Get();
    }

    public function toArrayWithClientNameAndToken(string $name, string $token): array
    {
        return array_merge($this->toArray(), compact('name', 'token'));
    }

    public function toArray(): array
    {
        return array_merge($this->server->toArray(), [
            'get' => $this->get->toJson(),
            'post' => $this->post->toJson(),
        ]);
    }

    public function server(): Server
    {
        return $this->server;
    }

    public function post(): Post
    {
        return $this->post;
    }

    public function get(): Get
    {
        return $this->get;
    }
}
