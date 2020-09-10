<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Models\RequestsClientRemote;
use App\Models\RequestsServer;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Zablose\Allog\Data\Get;
use Zablose\Allog\Data\Post;

class ServerTest extends TestCase
{
    /** @test */
    public function saves_get_data_without_protection()
    {
        $uuid = $this->fake()->uuid;
        $uri = '/server?name=hello&password=qwerty&uuid='.$uuid;

        $this->get($uri)->assertOk()->assertSeeText('Allog Server');

        $model = RequestsServer::where($this->buildRequestRowWithGet($uri))->first();

        $this->assertTrue($model !== null);

        $model->delete();
    }

    /** @test */
    public function saves_post_data_with_protection()
    {
        $uri = '/server?name=hello';
        $uuid = $this->fake()->uuid;

        $data = [
            'uuid' => $uuid,
            'password' => 'qwerty',
        ];

        $this->post($uri, $data)->assertOk()->assertSeeText('Allog Server');

        $data['password'] = '*';
        $model = RequestsServer::where($this->buildRequestRowWithPost($uri, $data))->first();

        $this->assertTrue($model !== null);

        $model->delete();
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
        $_SERVER['REMOTE_ADDR'] = '192.168.0.1';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = $uri;

        $_GET = $this->parseUriToGet($uri);

        $_POST = $data;

        return parent::post($uri, $data, $headers);
    }

    /** @test */
    public function saves_post_data_with_auth()
    {
        $uri = '/server-with-remote-client';
        $uuid = $this->fake()->uuid;

        $post = [
            'uuid' => $uuid,
        ];

        $client_uri = '/some-uri-on-client-side?name=hello';

        $data = [
            'http_user_agent' => 'Google Chrome',
            'remote_addr' => '8.8.8.8',
            'request_method' => 'POST',
            'request_uri' => $client_uri,
            'get' => (new Get($this->parseUriToGet($client_uri)))->toJsonAsObject(),
            'post' => (new Post($post))->toJsonAsObject(),
        ];

        $auth = [
            Post::KEY_CLIENT_NAME => env('ALLOG_CLIENT_1_NAME'),
            Post::KEY_CLIENT_TOKEN => env('ALLOG_CLIENT_1_TOKEN'),
        ];

        $this->post($uri, array_merge($data, $auth))->assertOk()->assertSeeText('Allog Server');

        $model = RequestsClientRemote::where($data)->first();

        $this->assertNotNull($model);

        $model->delete();
    }

    /** @test */
    public function fails_auth_if_client_name_is_missing()
    {
        $uuid = $this->fake()->uuid;
        $uri = '/server-with-remote-client?uuid='.$uuid;

        $data = ['get' => '{"uuid":"'.$uuid.'"}'];
        $auth = [Post::KEY_CLIENT_TOKEN => env('ALLOG_CLIENT_1_TOKEN')];

        $this->post($uri, array_merge($data, $auth))->assertOk()->assertSeeText('Allog Server');

        $this->assertNull(RequestsClientRemote::where($data)->first());

        $model = RequestsServer::where(['request_uri' => $uri])->first();

        $this->assertNotNull($model);

        $model->delete();
    }
}
