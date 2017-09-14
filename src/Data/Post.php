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

    public function __construct()
    {
        $this->post = $_POST;
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
        return json_encode($this->post);
    }

}
