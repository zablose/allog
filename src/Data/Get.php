<?php

namespace Zablose\Allog\Data;

class Get
{

    /**
     * $_GET array as is.
     *
     * @var string
     */
    private $data;

    public function __construct()
    {
        $this->data = $_GET;
    }

    /**
     * Get $_GET array as JSON string.
     *
     * @return string
     */
    public function json()
    {
        return json_encode($this->data);
    }

}
