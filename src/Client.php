<?php declare(strict_types=1);

namespace Zablose\Allog;

use Zablose\Allog\Data\Container;

class Client
{
    const STATE_DEVELOPMENT = 'development';
    const STATE_DISABLED = 'disabled';
    const STATE_LOCAL = 'local';
    const STATE_PRODUCTION = 'production';

    const MSG_IS_DISABLED_OR_NOT_CONFIGURED = 'Allog Client: Is disabled or not configured.';

    /** @var resource */
    private $ch;
    private Container $data;
    private string $response = '';
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->data = new Container($config);
    }

    private function isDisabledOrNotConfigured(): bool
    {
        return $this->config->client_state === self::STATE_DISABLED
            || empty($this->config->client_name)
            || empty($this->config->client_token) && $this->config->client_state !== self::STATE_LOCAL
            || empty($this->config->server_url);
    }

    public function send(): self
    {
        if ($this->isDisabledOrNotConfigured()) {
            if ($this->config->debug) {
                trigger_error(self::MSG_IS_DISABLED_OR_NOT_CONFIGURED, E_USER_NOTICE);
            }

            return $this;
        }

        $this->ch = curl_init();

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => false,
            CURLOPT_URL => $this->config->server_url,
            CURLOPT_USERAGENT => 'Allog Client',
            CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $this->data->toArrayWithClientNameAndToken(
                $this->config->client_name,
                $this->config->client_token
            ),
            CURLOPT_CONNECTTIMEOUT_MS => 200,
        ];

        if ($this->config->client_state === self::STATE_LOCAL) {
            $options[CURLOPT_PROTOCOLS] = CURLPROTO_HTTP | CURLPROTO_HTTPS;
        }

        // Allow self-signed certificates.
        if (in_array($this->config->client_state, [self::STATE_DEVELOPMENT, self::STATE_LOCAL])) {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
        }

        curl_setopt_array($this->ch, $options);

        $response = curl_exec($this->ch);
        if ($options[CURLOPT_RETURNTRANSFER] === true && $response !== false) {
            $this->response = $response;
        }

        if (curl_errno($this->ch) && $this->config->debug) {
            trigger_error(print_r($this->getError(), true), E_USER_WARNING);
        }

        return $this;
    }

    public function getResponse(): string
    {
        return htmlspecialchars($this->response);
    }

    public function getHttpCode(): int
    {
        return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
    }

    /**
     * Get last error's number and message.
     *
     * @return object
     */
    public function getError(): object
    {
        return $this->ch ? (object) [
            'error_number' => curl_errno($this->ch),
            'error_message' => curl_error($this->ch),
            'http_code' => $this->getHttpCode(),
            'response' => $this->getResponse(),
        ] : (object) [
            'error_number' => 'n/a',
            'error_message' => self::MSG_IS_DISABLED_OR_NOT_CONFIGURED,
            'http_code' => 'n/a',
            'response' => 'n/a',
        ];
    }

    public function getCurlHandle()
    {
        return $this->ch;
    }

    public function __destruct()
    {
        if ($this->ch) {
            curl_close($this->ch);
        }
    }
}
