<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Zablose\Allog\Client;
use Zablose\Allog\Config;

class ClientTest extends TestCase
{
    /** @test */
    public function is_disabled()
    {
        $this->assertStringContainsString(
            Client::MSG_IS_DISABLED_OR_NOT_CONFIGURED,
            (new Client(new Config()))->send()->getError()->error_message
        );
    }

    /** @test */
    public function is_disabled_with_error()
    {
        $this->expectError();
        $this->expectErrorMessage(Client::MSG_IS_DISABLED_OR_NOT_CONFIGURED);

        $config = new Config();
        $config->debug = true;

        (new Client($config))->send();
    }

    /** @test */
    public function is_not_configured_no_name_no_token()
    {
        $config = new Config();
        $config->client_state = Client::STATE_PRODUCTION;

        $this->assertStringContainsString(
            Client::MSG_IS_DISABLED_OR_NOT_CONFIGURED,
            (new Client($config))->send()->getError()->error_message
        );
    }

    /** @test */
    public function is_not_configured_no_token()
    {
        $config = new Config();
        $config->client_state = Client::STATE_PRODUCTION;
        $config->client_name = 'Testing';

        $this->assertStringContainsString(
            Client::MSG_IS_DISABLED_OR_NOT_CONFIGURED,
            (new Client($config))->send()->getError()->error_message
        );
    }

    /** @test */
    public function triggers_curl_error()
    {
        $this->expectError();
        $this->expectErrorMessage('[error_number] => 3');

        $config = new Config();
        $config->debug = true;
        $config->client_state = Client::STATE_PRODUCTION;
        $config->client_name = 'Testing';
        $config->client_token = 'token';
        $config->server_url = '/curle_url_malformat';

        (new Client($config))->send();
    }
}
