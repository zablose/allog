<?php declare(strict_types=1);

namespace Zablose\Allog\Data;

use Zablose\Allog\Config;

class Post
{
    private array $data;

    /**
     * Data array keys to be protected by replacing values with '*'.
     *
     * @var array
     */
    private array $keys;

    public function __construct(Config $config)
    {
        $this->data = $_POST;
        $this->keys = $config->protected;
    }

    /**
     * Get client name from $_POST['name'] array.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->data['name'] ?? '';
    }

    /**
     * Get client token from $_POST['token'] array.
     *
     * @return string
     */
    public function token(): string
    {
        return $this->data['token'] ?? '';
    }

    /**
     * Get $_POST data as JSON string.
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->protect($this->keys)->data);
    }

    /**
     * Get $_POST data, but filter invalid keys first.
     *
     * @return array
     */
    public function toArray(): array
    {
        $base = [
            'http_user_agent' => null,
            'http_referer' => null,
            'remote_addr' => null,
            'request_method' => null,
            'request_uri' => null,
            'request_time' => null,
            'get' => null,
            'post' => null,
        ];

        return array_intersect_key($this->data, $base);
    }

    /**
     * Protect data in array by replacing values with '*' for selected keys.
     *
     * @param  array  $keys
     *
     * @return self
     */
    private function protect($keys): self
    {
        if (count($keys)) {
            array_walk_recursive($this->data, function (&$value, $key) use ($keys)
            {
                if (array_search($key, $keys) !== false) {
                    $value = '*';
                }
            });
        }

        return $this;
    }
}
