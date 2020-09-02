<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Zablose\Allog\Table;

class TablesTest extends TestCase
{
    /** @test */
    public function requests_table_name_for_client()
    {
        $tables = new Table('');

        $this->assertSame('requests_allog_client', $tables->requests('allog_client'));
    }
}
