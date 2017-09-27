<?php

namespace Zablose\Allog;

use Zablose\Allog\Data\Container;

class Client
{

    const STATE_DISABLED    = 'disabled';
    const STATE_DEVELOPMENT = 'development';
    const STATE_PRODUCTION  = 'production';
    const STATE_LOCAL       = 'local';

    /**
     * @var resource
     */
    private $ch;

    /**
     * URL to send data to.
     *
     * @var string
     */
    private $url;

    /**
     * Client name the data from.
     *
     * @var string
     */
    private $name;

    /**
     * Unique token for the app to access logging server.
     *
     * @var string
     */
    private $token;

    /**
     * A container class for storing Server, Post and Get data in one place,
     * used for sending from the Client to the Server.
     *
     * @var Container
     */
    private $data;

    /**
     * @var string
     */
    private $response;

    /**
     * Client state.
     *
     * @var string
     */
    private $state;

    /**
     * Valid states.
     *
     * @var array
     */
    private $states = [
        self::STATE_DISABLED    => self::STATE_DISABLED,
        self::STATE_DEVELOPMENT => self::STATE_DEVELOPMENT,
        self::STATE_PRODUCTION  => self::STATE_PRODUCTION,
        self::STATE_LOCAL       => self::STATE_LOCAL,
    ];

    public function __construct(array $config = [])
    {
        $this->data = new Container($config);

        $this
            ->state($config['client']['state'] ?? null)
            ->name($config['client']['name'] ?? null)
            ->token($config['client']['token'] ?? null)
            ->url($config['server']['url'] ?? null);
    }

    /**
     * Set Allog server URL to send the data to.
     *
     * @param string $value
     *
     * @return Client
     */
    public function url($value)
    {
        $this->url = $value;

        return $this;
    }

    /**
     * Set client name.
     *
     * @param string $name
     *
     * @return Client
     */
    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set client token.
     *
     * @param string $value
     *
     * @return Client
     */
    public function token($value)
    {
        $this->token = $value;

        return $this;
    }

    /**
     * @return bool
     */
    private function notConfiguredOrDisabled()
    {
        return $this->state === self::STATE_DISABLED || empty($this->name) || empty($this->token) || empty($this->url);
    }

    /**
     * Send data to the URL by using POST method.
     *
     * @return Client
     */
    public function send()
    {
        if ($this->notConfiguredOrDisabled())
        {
            return $this;
        }

        $this->ch = curl_init();

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR    => false,
            CURLOPT_URL            => $this->url,
            CURLOPT_USERAGENT      => 'Allog Client',
            CURLOPT_PROTOCOLS      => $this->state === self::STATE_LOCAL ? CURLPROTO_HTTP : CURLPROTO_HTTPS,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $this->data->toArrayWith($this->name, $this->token),
        ];

        // Allow self-signed certificates.
        if ($this->state === self::STATE_DEVELOPMENT)
        {
            $options[CURLOPT_SSL_VERIFYHOST] = 0;
        }

        curl_setopt_array($this->ch, $options);

        $this->response = curl_exec($this->ch);

        return $this;
    }

    /**
     * Set client state.
     *
     * @param string $value
     *
     * @return $this
     */
    public function state($value)
    {
        $this->state = $this->states[$value] ?? self::STATE_DISABLED;

        return $this;
    }

    /**
     * @return string
     */
    public function response()
    {
        return $this->response;
    }

    /**
     * Get HTTP response code.
     *
     * @return bool
     */
    public function code()
    {
        return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
    }

    public function __destruct()
    {
        curl_close($this->ch);
    }

    /**
     * Get last error's number and message.
     *
     * @return object
     */
    public function error()
    {
        return (object) [
            'number'  => curl_errno($this->ch),
            'message' => curl_error($this->ch),
        ];
    }

}
