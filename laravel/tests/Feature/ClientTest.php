<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Models\RequestsClient;
use Tests\TestCase;

class ClientTest extends TestCase
{
    /** @test */
    public function sends_get_data_without_protection()
    {
        $uuid = $this->fake()->uuid;

        $uri = '/client?uuid='.$uuid;

        $this->get($uri)->assertOk()->assertSeeText('Allog Client');

        $model = RequestsClient::where($this->buildRequestRowWithGet($uri))->first();

        $this->assertTrue($model !== null);

        $model->delete();
    }

    /** @test */
    public function sends_post_data_with_protection()
    {
        $uri = '/client';

        $data = [
            'uuid' => $this->fake()->uuid,
            'password' => $this->fake()->password,
        ];

        $this->post($uri, $data)->assertOk()->assertSeeText('Allog Client');

        $data['password'] = '*';
        $model = RequestsClient::where($this->buildRequestRowWithPost($uri, $data))->first();

        $this->assertTrue($model !== null);

        $model->delete();
    }
}
