<?php

declare(strict_types=1);

namespace Zablose\Allog\Data;

/**
 * Class with data from the $_POST array.
 */
class Post
{
    public const KEY_CLIENT_NAME = 'allog_client_name';
    public const KEY_CLIENT_TOKEN = 'allog_client_token';

    private array $data;
    private array $protected_keys = [];

    public function __construct(array $data = null)
    {
        $this->data = $data ?? $_POST;
    }

    public function setProtectedKeys(array $keys): self
    {
        $this->protected_keys = $keys;

        return $this;
    }

    public function getAllogClientName(): string
    {
        return $this->data[self::KEY_CLIENT_NAME] ?? '';
    }

    public function getAllogClientToken(): string
    {
        return $this->data[self::KEY_CLIENT_TOKEN] ?? '';
    }

    public function toJsonAsObject(): string
    {
        return json_encode($this->protect($this->protected_keys)->data, JSON_FORCE_OBJECT);
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
            array_walk_recursive(
                $this->data,
                function (&$value, $key) use ($keys)
                {
                    if (array_search($key, $keys) !== false) {
                        $value = '*';
                    }
                }
            );
        }

        return $this;
    }
}
