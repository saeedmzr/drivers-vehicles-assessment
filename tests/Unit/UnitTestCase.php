<?php

namespace Tests\Unit;

use Tests\TestCase;

abstract class UnitTestCase extends TestCase
{
    protected bool $seed = false;

    protected function setUp(): void
    {
        parent::setUp();
    }
}
