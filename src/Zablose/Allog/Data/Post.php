<?php

namespace Zablose\Allog\Data;

class Post
{

    /**
     * $_POST array as JSON string.
     *
     * @var string
     */
    public $jpost;

    /**
     * Fill Post object with the global $_POST array as JSON string.
     */
    public function __construct()
    {
        if (!empty($_POST))
        {
            $this->jpost = json_encode($_POST);
        }
    }

}
