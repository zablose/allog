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

    public function __construct()
    {
        $this->server = new Server();
        $this->post   = new Post();
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
        return array_merge($this->_toArray(), compact('name', 'token'));
    }

    /**
     * Get Container object as array from the object itself or given array.
     *
     * @param array $data
     *
     * @return array
     */
    public function toArray($data = null)
    {
        return is_null($data) ? $this->_toArray() : $this->_toValidArray($data);
    }

    /**
     * Get Container object as array.
     *
     * @return array
     */
    protected function _toArray()
    {
        return array_merge($this->server->toArray(), [
            'get'  => $this->get->json(),
            'post' => $this->post->json(),
        ]);
    }

    /**
     * Get Container object as array from the given array, by validating keys.
     *
     * @param array $data
     *
     * @return array
     */
    protected function _toValidArray($data)
    {
        $base = [
            'http_user_agent' => null,
            'http_referer'    => null,
            'remote_addr'     => null,
            'request_method'  => null,
            'request_uri'     => null,
            'request_time'    => null,
            'get'             => null,
            'post'            => null,
        ];

        return array_intersect_key($data, $base);
    }

}
