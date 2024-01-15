<?php

declare(strict_types=1);

namespace Ascetik\Hypothetik\Tests;

use PHPUnit\Framework\TestCase;
use Ascetik\Hypothetik\Core\Maybe;
use Ascetik\Hypothetik\Core\When;

class FalsyConditionTest extends TestCase
{
    private When $boolean;

    protected function setUp(): void
    {
        $this->boolean = When::as(false);
    }

    public function testHypothetikBooleanValue()
    {
        $this->assertFalse($this->boolean->value());
    }

    public function testApplyingCallableOnBoolean()
    {
        $applied = $this->boolean->apply(fn () => 'all is true');
        $this->assertNull($applied);
    }


    public function testShouldThenReturnAMaybeInstance()
    {
        $maybe = $this->boolean->then(fn () => 'truthy result');
        $this->assertInstanceOf(Maybe::class, $maybe);
        $this->assertNull($maybe->value());
    }

    public function testConditionAlternativeValue()
    {
        $maybe = $this->boolean->then(fn () => 'truthy result')
            ->otherwise('falsy result');
        $this->assertSame('falsy result', $maybe->value());
    }

    public function testConditionWithEither()
    {
        $maybe = $this->boolean
            ->either(fn () => 'truthy condition')
            ->or(fn () => 'falsy condition')
            ->try();
        $this->assertSame('falsy condition', $maybe->value());
    }

    public function testShouldBeAbleToHandleArguments()
    {
        $maybe = $this->boolean->either(fn (string $name) => 'hello ' . $name, 'John')
            ->or(fn (string $message) => 'Error : ' . $message, 'falsy result');
        $this->assertSame('Error : falsy result', $maybe->value());
    }
}
