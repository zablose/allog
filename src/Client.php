<?php declare(strict_types=1);

namespace Zablose\Allog;

use Zablose\Allog\Data\Container;

class Client
{
    const STATE_DISABLED    = 'disabled';
    const STATE_DEVELOPMENT = 'development';
    const STATE_PRODUCTION  = 'production';
    const STATE_LOCAL       = 'local';

    /** @var resource */
    private $ch;

    /**
     * URL to send data to.
     *
     * @var string
     */
    private string $url;

    /**
     * Client name the data from.
     *
     * @var string
     */
    private string $name;

    /**
     * Unique token for the app to access logging server.
     *
     * @var string
     */
    private string $token;

    /**
     * A container class for storing Server, Post and Get data in one place,
     * used for sending from the Client to the Server.
     *
     * @var Container
     */
    private Container $data;

    private string $response;
    private string $state;
    private array $states = [
        self::STATE_DISABLED => self::STATE_DISABLED,
        self::STATE_DEVELOPMENT => self::STATE_DEVELOPMENT,
        self::STATE_PRODUCTION => self::STATE_PRODUCTION,
        self::STATE_LOCAL => self::STATE_LOCAL,
    ];

    public function __construct(array $config = [])
    {
        $this->data = new Container($config);

        $this
            ->setState($config['client']['state'] ?? null)
            ->setName($config['client']['name'] ?? null)
            ->setToken($config['client']['token'] ?? null)
            ->setUrl($config['server']['url'] ?? null);
    }

    /**
     * Set Allog server URL to send the data to.
     *
     * @param  string  $value
     *
     * @return self
     */
    public function setUrl(string $value): self
    {
        $this->url = $value;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setToken(string $value): self
    {
        $this->token = $value;

        return $this;
    }

    private function notConfiguredOrDisabled(): bool
    {
        return $this->state === self::STATE_DISABLED || empty($this->name) || empty($this->token) || empty($this->url);
    }

    /**
     * Send data to the URL by using POST method.
     *
     * @return self
     */
    public function send(): self
    {
        if ($this->notConfiguredOrDisabled()) {
            return $this;
        }

        $this->ch = curl_init();

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => false,
            CURLOPT_URL => $this->url,
            CURLOPT_USERAGENT => 'Allog Client',
            CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $this->data->toArrayWith($this->name, $this->token),
            CURLOPT_CONNECTTIMEOUT_MS => 200,
        ];

        if ($this->state === self::STATE_LOCAL) {
            $options[CURLOPT_PROTOCOLS] = CURLPROTO_HTTP | CURLPROTO_HTTPS;
        }

        // Allow self-signed certificates.
        if (in_array($this->state, [self::STATE_DEVELOPMENT, self::STATE_LOCAL])) {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
        }

        curl_setopt_array($this->ch, $options);

        $this->response = curl_exec($this->ch);

        if (curl_errno($this->ch)) {
            trigger_error(print_r($this->getError(), true), E_USER_WARNING);
        }

        return $this;
    }

    public function setState(string $value): self
    {
        $this->state = $this->states[$value] ?? self::STATE_DISABLED;

        return $this;
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function getHttpCode(): string
    {
        return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
    }

    public function __destruct()
    {
        if ($this->ch) {
            curl_close($this->ch);
        }
    }

    /**
     * Get last error's number and message.
     *
     * @return object
     */
    public function getError(): object
    {
        return $this->ch ? (object) [
            'number' => curl_errno($this->ch),
            'message' => curl_error($this->ch),
        ] : (object) [
            'number' => 'n/a',
            'message' => 'Allog client is not configured or disabled.',
        ];
    }
}
