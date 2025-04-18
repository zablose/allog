<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\RequestsClientRemote;
use App\Models\RequestsServer;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;
use Zablose\Allog\Data\Post;

class ServerTest extends FeatureTestCase
{
    #[Test]
    public function saves_get_data_without_protection()
    {
        $uuid = $this->fake()->uuid;
        $uri = '/server?name=hello&password=qwerty&uuid='.$uuid;

        $this->setGlobalsServerGetPost($uri);

        $this->get($uri)->assertOk()->assertSeeText('Allog Server');

        $model = RequestsServer::where($this->makeRequestData($uri))->first();

        $this->assertNotNull($model);

        $model->delete();
    }

    #[Test]
    public function saves_post_data_without_protection()
    {
        $uuid = $this->fake()->uuid;
        $uri = '/server?name=hello';

        $data = [
            'uuid' => $uuid,
            'password' => 'qwerty',
        ];

        $this->setGlobalsServerGetPost($uri, $data, 'POST');

        $this->post($uri, $data)->assertOk()->assertSeeText('Allog Server');

        $model = RequestsServer::where($this->makeRequestData($uri, $data, 'POST'))->first();

        $this->assertNotNull($model);

        $model->delete();
    }

    #[Test]
    public function saves_post_data_with_auth()
    {
        $uuid = $this->fake()->uuid;
        $uri = '/server';

        $post = [
            'uuid' => $uuid,
        ];

        $client_uri = '/some-uri-on-client-side?name=hello';

        $request = $this->makeRequestData($client_uri, $post, 'POST');

        $auth = [
            Post::KEY_CLIENT_NAME => env('ALLOG_CLIENT_1_NAME'),
            Post::KEY_CLIENT_TOKEN => env('ALLOG_CLIENT_1_TOKEN'),
        ];

        $data = array_merge($request, $auth);

        $this->setGlobalsServerGetPost($uri, $data, 'POST');

        $this->post($uri, $data)->assertOk()->assertSeeText('Allog Server');

        $model = RequestsClientRemote::where($request)->first();

        $this->assertNotNull($model);

        $model->delete();
    }

    #[Test]
    public function fails_auth_if_client_name_is_missing()
    {
        $uuid = $this->fake()->uuid;
        $uri = '/server?uuid='.$uuid;

        $request = ['get' => '{"uuid":"'.$uuid.'"}'];
        $auth = [Post::KEY_CLIENT_TOKEN => env('ALLOG_CLIENT_1_TOKEN')];

        $data = array_merge($request, $auth);

        $this->setGlobalsServerGetPost($uri, $data, 'POST');

        $this->post($uri, $data)->assertOk()->assertSeeText('Allog Server');

        $this->assertNull(RequestsClientRemote::where($request)->first());

        $model = RequestsServer::where(['request_uri' => $uri])->first();

        $this->assertNotNull($model);

        $model->delete();
    }
}
