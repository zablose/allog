<?php

namespace Tests\Feature;

use Tests\TestCase;

class ServerTest extends TestCase
{
    /** @test */
    public function is_accessible()
    {
        $this->get('/')->assertOk()->assertSeeText('Allog Server');
    }
}
