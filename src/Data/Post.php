<?php

namespace Zablose\Allog\Data;

class Post
{

    /**
     * $_POST array as is.
     *
     * @var array
     */
    public $post;

    /**
     * @var array
     */
    private $protected;

    public function __construct(array $protected)
    {
        $this->post      = $_POST;
        $this->protected = $protected;
    }

    /**
     * Get client name from $_POST['name'] array.
     *
     * @return string|null
     */
    public function name()
    {
        return $this->post['name'] ?? null;
    }

    /**
     * Get client token from $_POST['token'] array.
     *
     * @return string|null
     */
    public function token()
    {
        return $this->post['token'] ?? null;
    }

    /**
     * Get $_POST data as JSON string.
     *
     * @return string
     */
    public function json()
    {
        return json_encode($this->protect($this->protected)->post);
    }

    /**
     * Get $_POST data as array with keys validation.
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

        return array_intersect_key($this->post, $base);
    }

    /**
     * Protect keys in post array by replacing values with '*'.
     *
     * @param array $protected
     *
     * @return $this
     */
    private function protect($protected)
    {
        if (count($protected))
        {
            array_walk_recursive($this->post, function (&$value, $key) use ($protected)
            {
                if (array_search($key, $protected) !== false)
                {
                    $value = '*';
                }
            });
        }

        return $this;
    }

}
