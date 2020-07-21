<?php declare(strict_types=1);

namespace Zablose\Allog\Data;

/**
 * A container class for storing Server, Post and Get data in one place.
 */
class Container
{
    /**
     * An instance of the Server class with some data from the $_SERVER array as attributes.
     *
     * @var Server
     */
    private Server $server;

    /**
     * An instance of the Post class that represents $_POST array.
     *
     * @var Post
     */
    private Post $post;

    /**
     * An instance of the Get class that represents $_GET array.
     *
     * @var Get
     */
    private Get $get;

    public function __construct(array $config)
    {
        $this->server = new Server();
        $this->post   = new Post($config);
        $this->get    = new Get();
    }

    /**
     * Get Container object as array with added client 'name' and 'token' elements.
     *
     * @param  string  $name  Client name.
     * @param  string  $token
     *
     * @return array
     */
    public function toArrayWith(string $name, string $token): array
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
