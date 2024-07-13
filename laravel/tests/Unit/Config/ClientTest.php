<?php

declare(strict_types=1);

namespace Tests\Unit\Config;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Zablose\Allog\Config\Client as Config;

class ClientTest extends TestCase
{
    #[Test]
    public function turns_debug_on_or_off()
    {
        $config = (new Config())->debugOn();

        $this->assertTrue($config->debug);

        $config->debugOff();

        $this->assertFalse($config->debug);
    }

    #[Test]
    public function read_config_from_different_files()
    {
        $dir = __DIR__.'/../../files';
        $config = (new Config())->read($dir.'/.env.client')->read($dir.'/.env.protect');
        $this->assertSame(
            [
                'debug' => false,
                'client_state' => 'production',
                'client_name' => 'forum',
                'client_token' => 'token',
                'server_url' => 'https://www.allog.zdev/',
                'protected' => [
                    1 => '_token',
                    2 => 'password',
                    3 => 'password_confirmation',
                ],
            ],
            get_object_vars($config)
        );
    }
}
