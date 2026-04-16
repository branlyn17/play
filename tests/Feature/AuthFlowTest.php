<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    public function test_auth_flow_placeholder(): void
    {
        $this->markTestSkipped('Auth flow assertions require a writable sqlite testing driver in this environment.');
    }
}
