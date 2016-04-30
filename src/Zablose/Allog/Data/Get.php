<?php

namespace Zablose\Allog\Data;

class Get
{

    /**
     * $_GET array as JSON string.
     *
     * @var string
     */
    public $jget;

    /**
     * Fill Get object with the global $_GET array as JSON string.
     */
    public function __construct()
    {
        if (!empty($_GET))
        {
            $this->jget = json_encode($_GET);
        }
    }

}
