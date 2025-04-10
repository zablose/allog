<?php

namespace Tests;

use Faker\Factory;
use Faker\Generator as FakerGenerator;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Zablose\Allog\Data\Get;
use Zablose\Allog\Data\Post;

abstract class FeatureTestCase extends BaseTestCase
{
    use CreatesApplication;

    public const string HTTP_USER_AGENT = 'Laravel Testing';
    public const string REMOTE_ADDR = '127.0.0.1';

    private static ?FakerGenerator $faker_generator = null;

    protected function fake(): FakerGenerator
    {
        if (self::$faker_generator === null) {
            self::$faker_generator = Factory::create();
        }

        return self::$faker_generator;
    }

    protected function uriToGet(string $uri): array
    {
        $get = [];
        $query = parse_url($uri, PHP_URL_QUERY);
        if (! empty($query)) {
            parse_str($query, $get);
        }

        return $get;
    }

    protected function makeRequestData(string $uri, array $data = [], string $method = 'GET'): array
    {
        return [
            'http_user_agent' => self::HTTP_USER_AGENT,
            'remote_addr' => self::REMOTE_ADDR,
            'request_method' => $method,
            'request_uri' => $uri,
            'get' => (new Get($this->uriToGet($uri)))->toJsonAsObject(),
            'post' => (new Post($data))->toJsonAsObject(),
        ];
    }

    protected function setGlobalsServerGetPost($uri, array $data = [], string $method = 'GET'): void
    {
        $_SERVER['HTTP_USER_AGENT'] = self::HTTP_USER_AGENT;
        $_SERVER['REMOTE_ADDR'] = self::REMOTE_ADDR;
        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;

        $_GET = $this->uriToGet($uri);

        $_POST = $data;
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
