<?php

namespace Zablose\Allog;

use Zablose\Allog\Data\Data;

class Client
{

    /**
     * URL to send data to.
     *
     * @var string
     */
    protected $url;

    /**
     * Application name the data from.
     *
     * @var string
     */
    protected $appname;

    /**
     * Unique token for the app to access logging server.
     *
     * @var string
     */
    protected $token;

    /**
     * A container class for storing Server, Post and Get data in one place,
     * used for sending from the Client to the Server.
     *
     * @var Zablose\Allog\Data\Data
     */
    protected $data;

    /**
     * Build a new Client instance with the Data object instance.
     */
    public function __construct()
    {
        $this->data = new Data();
    }

    /**
     * Get or set URL to send the data to.
     *
     * @param string $value
     *
     * @return \Zablose\Allog\Client|string
     */
    public function url($value = null)
    {
        if (is_null($value))
        {
            return $this->url;
        }

        $this->url = $value;

        return $this;
    }

    /**
     * Get or set application name.
     *
     * @param string $appname
     *
     * @return \Zablose\Allog\Client|string
     */
    public function appname($appname = null)
    {
        if (is_null($appname))
        {
            return $this->appname;
        }

        $this->appname = $appname;

        return $this;
    }

    /**
     * Get or set application token.
     *
     * @param string $value
     *
     * @return \Zablose\Allog\Client|string
     */
    public function token($value = null)
    {
        if (is_null($value))
        {
            return $this->token;
        }

        $this->token = $value;

        return $this;
    }

    /**
     * Send data to the URL by using POST method.
     *
     * @return boolean <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function send()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 0,
            CURLOPT_URL            => $this->url,
            CURLOPT_USERAGENT      => 'Allog Client',
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => $this->data->toArrayWith($this->appname(), $this->token())
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

}
