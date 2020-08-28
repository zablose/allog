<?php declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class ServerTest extends TestCase
{
    /** @test */
    public function saves_get_data_without_protection()
    {
        $uri = '/?name=hello&password=qwerty';

        $this->get($uri)->assertOk()->assertSeeText('Allog Server');

        $this->assertDatabaseHas('requests_allog', $this->buildRequestRowWithGet($uri));
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
        $this->assertDatabaseHas('requests_allog', $this->buildRequestRowWithPost($uri, $data));
    }
}
