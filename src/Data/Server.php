<?php

namespace Zablose\Allog\Data;

class Server
{

    /**
     * $_SERVER['HTTP_USER_AGENT']
     *
     * @var string
     */
    public $http_user_agent;

    /**
     * $_SERVER['HTTP_REFERER']
     *
     * @var string
     */
    public $http_referer;

    /**
     * $_SERVER['REMOTE_ADDR']
     *
     * @var string
     */
    public $remote_addr;

    /**
     * $_SERVER['REQUEST_METHOD']
     *
     * @var string
     */
    public $request_method;

    /**
     * $_SERVER['REQUEST_URI']
     *
     * @var string
     */
    public $request_uri;

    /**
     * $_SERVER['REQUEST_TIME']
     *
     * @var integer
     */
    public $request_time;

    /**
     * Fill Server object attributes with the values from the global $_SERVER array.
     */
    public function __construct()
    {
        foreach (array_keys(get_object_vars($this)) as $attribute)
        {
            $this->$attribute = $_SERVER[strtoupper($attribute)] ?? null;
        }
    }

    /**
     * Get Server object as array.
     *
     * @return array
     */
    public function toArray()
    {
        $data = (array) $this;

        $data['request_time'] = date('Y-m-d H:i:s', $this->request_time);

        return $data;
    }

}
