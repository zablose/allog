<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\RequestsClientLocal;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class ClientTest extends FeatureTestCase
{
    #[Test]
    public function sends_get_data_without_protection()
    {
        $uuid = $this->fake()->uuid;

        $uri = '/client?uuid='.$uuid;

        $this->setGlobalsServerGetPost($uri);

        $this->get($uri)->assertOk()->assertSeeText('Allog Client');

        $model = RequestsClientLocal::where($this->makeRequestData($uri))->first();

        $this->assertNotNull($model);

        $model->delete();
    }

    #[Test]
    public function sends_post_data_with_protection()
    {
        $uri = '/client';

        $data = [
            'uuid' => $this->fake()->uuid,
            'password' => $this->fake()->password,
        ];

        $this->setGlobalsServerGetPost($uri, $data, 'POST');

        $this->post($uri, $data)->assertOk()->assertSeeText('Allog Client');

        $data['password'] = '*';
        $model = RequestsClientLocal::where($this->makeRequestData($uri, $data, 'POST'))->first();

        $this->assertNotNull($model);

        $model->delete();
    }
}
