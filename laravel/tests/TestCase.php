<?php

namespace Tests;

use Faker\Factory;
use Faker\Generator as FakerGenerator;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    const HTTP_USER_AGENT = 'Laravel Testing';
    const REMOTE_ADDR = '127.0.0.1';

    private static ?FakerGenerator $faker_generator = null;

    public function fake(): FakerGenerator
    {
        if (self::$faker_generator === null) {
            self::$faker_generator = Factory::create();
        }

        return self::$faker_generator;
    }

    protected function parseUriToGet(string $uri): array
    {
        $get = [];
        $query = parse_url($uri, PHP_URL_QUERY);
        if (! empty($query)) {
            parse_str($query, $get);
        }

        return $get;
    }

    protected function buildRequestRow(string $uri, string $method, array $post): array
    {
        return [
            'http_user_agent' => self::HTTP_USER_AGENT,
            'remote_addr' => self::REMOTE_ADDR,
            'request_method' => $method,
            'request_uri' => $uri,
            'get' => json_encode($this->parseUriToGet($uri), JSON_FORCE_OBJECT),
            'post' => json_encode($post, JSON_FORCE_OBJECT),
        ];
    }

    protected function buildRequestRowWithGet(string $uri): array
    {
        return $this->buildRequestRow($uri, 'GET', []);
    }

    protected function buildRequestRowWithPost(string $uri, array $post): array
    {
        return $this->buildRequestRow($uri, 'POST', $post);
    }

    /**
     * @param  string  $uri
     * @param  array   $headers
     *
     * @return TestResponse
     */
    public function get($uri, array $headers = [])
    {
        $_SERVER['HTTP_USER_AGENT'] = self::HTTP_USER_AGENT;
        $_SERVER['REMOTE_ADDR'] = self::REMOTE_ADDR;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = $uri;

        $_GET = $this->parseUriToGet($uri);

        return parent::get($uri, $headers);
    }

    /**
     * @param  string  $uri
     * @param  array   $data
     * @param  array   $headers
     *
     * @return TestResponse
     */
    public function post($uri, array $data = [], array $headers = [])
    {
        $_SERVER['HTTP_USER_AGENT'] = self::HTTP_USER_AGENT;
        $_SERVER['REMOTE_ADDR'] = self::REMOTE_ADDR;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = $uri;

        $_GET = $this->parseUriToGet($uri);

        $_POST = $data;

        return parent::post($uri, $data, $headers);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset(
            $_SERVER['HTTP_USER_AGENT'],
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI']
        );

        $_GET = [];
        $_POST = [];
    }
}
