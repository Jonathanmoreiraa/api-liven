<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

class ExampleTest extends TestCase
{
    use CreatesApplication;
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }
}
