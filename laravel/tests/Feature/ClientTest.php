<?php

namespace Tests\Feature;

use Faker\Factory as Faker;
use GuzzleHttp\Client;
use Tests\TestCase;

class ClientTest extends TestCase
{
    /** @test */
    public function is_accessible()
    {
        $this->get('/client')->assertOk()->assertSeeText('Allog Client');
    }

    /** @test */
    public function sends_get_data()
    {
        $faker = Faker::create();

        $uuid = $faker->uuid;

        $guzzle = new Client();
        $body = $guzzle->get(secure_url('/client?name=testing&uuid='.$uuid))->getBody();

        $this->assertStringContainsString('Allog Client', $body);

        $this->assertDatabaseHas('requests_allog_local', ['get' => '{"url":"client","uuid":"'.$uuid.'"}']);
    }
}
