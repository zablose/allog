<?php

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
    public $server;

    /**
     * An instance of the Post class that represents $_POST array.
     *
     * @var Post
     */
    public $post;

    /**
     * An instance of the Get class that represents $_GET array.
     *
     * @var Get
     */
    public $get;

    public function __construct(array $config)
    {
        $this->server = new Server();
        $this->post   = new Post($config['protected'] ?? []);
        $this->get    = new Get();
    }

    /**
     * Get Container object as array with added client 'name' and 'token' elements.
     *
     * @param string $name Client name.
     * @param string $token
     *
     * @return array
     */
    public function toArrayWith($name, $token)
    {
        return array_merge($this->toArray(), compact('name', 'token'));
    }

    /**
     * Get Container object as array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge($this->server->toArray(), [
            'get'  => $this->get->json(),
            'post' => $this->post->json(),
        ]);
    }

}
