<?php

declare(strict_types=1);

namespace Tests\Unit;

use Exception;
use PHPUnit\Framework\Attributes\Test;
use Tests\UnitTestCase;
use Zablose\Allog\Client;
use Zablose\Allog\Config\Client as Config;

class ClientTest extends UnitTestCase
{
    #[Test]
    public function is_disabled()
    {
        $this->assertStringContainsString(
            Client::MSG_IS_DISABLED_OR_NOT_CONFIGURED,
            (new Client(new Config()))->send()->getError()->error_message
        );
    }

    #[Test]
    public function is_disabled_with_error()
    {
        //$this->convertUserNoticeToException();

        $config = new Config();
        $config->debug = true;

        //(new Client($config))->send();

        $this->assertThrows(fn()=>(new Client($config))->send(), Exception::class, Client::MSG_IS_DISABLED_OR_NOT_CONFIGURED);
    }

    #[Test]
    public function is_not_configured_no_name_no_token()
    {
        $config = new Config();
        $config->client_state = Client::STATE_PRODUCTION;

        $this->assertStringContainsString(
            Client::MSG_IS_DISABLED_OR_NOT_CONFIGURED,
            (new Client($config))->send()->getError()->error_message
        );
    }

    #[Test]
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

    #[Test]
    public function triggers_curl_error()
    {
        //$this->convertUserNoticeToException();

        //$this->expectException(Exception::class);
        //$this->expectExceptionMessage('[error_number] => 3');

        $config = new Config();
        $config->debug = true;
        $config->client_state = Client::STATE_PRODUCTION;
        $config->client_name = 'Testing';
        $config->client_token = 'token';
        $config->server_url = '/curle_url_malformat';

        //(new Client($config))->send();

        //restore_error_handler();

        $this->assertThrows(fn()=>(new Client($config))->send(), Exception::class, '[error_number] => 3');
    }
}
