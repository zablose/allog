<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Models\RequestsServer;
use Tests\TestCase;

class ServerTest extends TestCase
{
    /** @test */
    public function saves_get_data_without_protection()
    {
        $uuid = $this->fake()->uuid;
        $uri = '/?name=hello&password=qwerty&uuid='.$uuid;

        $this->get($uri)->assertOk()->assertSeeText('Allog Server');

        $model = RequestsServer::where($this->buildRequestRowWithGet($uri))->first();

        $this->assertTrue($model !== null);

        $model->delete();
    }

    /** @test */
    public function saves_post_data_with_protection()
    {
        $uri = '/?name=hello';
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
}
