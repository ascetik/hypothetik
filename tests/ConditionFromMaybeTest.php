<?php

declare(strict_types=1);

namespace Ascetik\Hypothetik\Tests;

use Ascetik\Hypothetik\Core\When;
use Ascetik\Hypothetik\Core\Maybe;
use PHPUnit\Framework\TestCase;

class ConditionFromMaybeTest extends TestCase
{
    public function testMaybeBuildsAConditionOnBoolValue()
    {
        $maybe = Maybe::some(true);
        $this->assertInstanceOf(When::class, $maybe);
    }

    public function testMaybeValueBecomingATruthyCondition()
    {
        $maybe = Maybe::some('/about')
            ->then(fn (string $value) => str_starts_with($value, '/'));
        $this->assertInstanceOf(When::class, $maybe);
        $this->assertTrue($maybe->value());
    }

    public function testMaybeValueBecomingAFalsyCondition()
    {
        $maybe = Maybe::some('/about')
            ->then(fn (string $value) => str_ends_with($value, '/'));
        $this->assertFalse($maybe->value());
    }

    public function testALittleMoreCalls()
    {
        $maybe = Maybe::some('/about')
            ->then(fn (string $value) => trim($value, '/'))
            ->then(fn (string $value) => str_starts_with($value, '/'));
        $this->assertFalse($maybe->value());
    }

    public function testSameThingUsingEither()
    {
        $either = Maybe::some('/about')
            ->then(fn (string $value) => trim($value, '/'))
            ->then(fn (string $value) => str_starts_with($value, '/'))
            ->either(fn() => 'truthy result')
            ->or(fn() => 'falsy result');
        $this->assertSame('falsy result', $either->value());
    }
}
