<?php

namespace Tests\Feature;

use Tests\TestCase;

class ClientTest extends TestCase
{
    /** @test */
    public function is_accessible()
    {
        $this->get('/client')->assertOk()->assertSeeText('Allog Client');
    }
}
