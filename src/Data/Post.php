<?php

namespace Zablose\Allog\Data;

class Post
{

    /**
     * $_POST array as is.
     *
     * @var array
     */
    private $data;

    /**
     * Data array keys to be protected by replacing values with '*'.
     *
     * @var array
     */
    private $keys;

    public function __construct(array $config)
    {
        $this->data = $_POST;
        $this->keys = $config['protected'] ?? [];
    }

    /**
     * Get client name from $_POST['name'] array.
     *
     * @return string|null
     */
    public function name()
    {
        return $this->data['name'] ?? null;
    }

    /**
     * Get client token from $_POST['token'] array.
     *
     * @return string|null
     */
    public function token()
    {
        return $this->data['token'] ?? null;
    }

    /**
     * Get $_POST data as JSON string.
     *
     * @return string
     */
    public function json()
    {
        return json_encode($this->protect($this->keys)->data);
    }

    /**
     * Get $_POST data, but filter invalid keys first.
     *
     * @return array
     */
    public function toArray()
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

        return array_intersect_key($this->data, $base);
    }

    /**
     * Protect data in array by replacing values with '*' for selected keys.
     *
     * @param array $keys
     *
     * @return $this
     */
    private function protect($keys)
    {
        if (count($keys))
        {
            array_walk_recursive($this->data, function (&$value, $key) use ($keys)
            {
                if (array_search($key, $keys) !== false)
                {
                    $value = '*';
                }
            });
        }

        return $this;
    }

}
