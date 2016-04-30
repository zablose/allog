<?php

namespace Zablose\Allog\Data;

use Zablose\Allog\Data\Get;
use Zablose\Allog\Data\Post;
use Zablose\Allog\Data\Server;

/**
 * A container class for storing Server, Post and Get data in one place,
 * used for sending from the Client to the Server.
 */
class Data
{

    /**
     * An instance of the Server class with some data from the $_SERVER array as attributes.
     *
     * @var Zablose\Allog\Data\Server
     */
    public $server;

    /**
     * An instance of the Post class that represents $_POST array as a JSON string.
     *
     * @var Zablose\Allog\Data\Post
     */
    public $post;

    /**
     * An instance of the Get class that represents $_GET array as a JSON string.
     *
     * @var Zablose\Allog\Data\Get
     */
    public $get;

    /**
     * Build Data object with Server, Post and Get data objects.
     */
    public function __construct()
    {
        $this->server = new Server();
        $this->post   = new Post();
        $this->get    = new Get();
    }

    /**
     * Get Data object as array with added 'app' and 'token' elements.
     *
     * @param string $appname Application name.
     * @param string $token
     *
     * @return array
     */
    public function toArrayWith($appname, $token)
    {
        return array_merge($this->_toArray(), compact('appname', 'token'));
    }

    /**
     * Get Data object as array from the object itself or given array.
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
     * Get Data object as array.
     *
     * @return array
     */
    protected function _toArray()
    {
        return array_merge($this->server->toArray(), [
            'get'  => $this->get->jget,
            'post' => $this->post->jpost
        ]);
    }

    /**
     * Get Data object as array from the given array, by validating keys.
     *
     * @param array $data
     *
     * @return array
     */
    protected function _toValidArray($data)
    {
        return array_only($data, array_keys($this->_toArray()));
    }

}
