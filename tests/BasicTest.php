<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase
{
    public function testAddition(): void
    {
        $result = 2 + 3;
        $this->assertEquals(5, $result, '2 + 3 should equal 5');
    }

    public function testStringContains(): void
    {
        $string = 'Symfony is great!';
        $this->assertStringContainsString('great', $string, 'String should contain "great"');
    }
}