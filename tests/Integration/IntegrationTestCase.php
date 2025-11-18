<?php

namespace Tests\Integration;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class IntegrationTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }
}
