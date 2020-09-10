<?php declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Zablose\Allog\Config;

class ConfigTest extends TestCase
{
    /** @test */
    public function turns_debug_on_or_off()
    {
        $config = (new Config())->debugOn();

        $this->assertTrue($config->debug);

        $config->debugOff();

        $this->assertFalse($config->debug);
    }
}
