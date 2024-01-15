<?php

declare(strict_types=1);

namespace Ascetik\Hypothetik\Tests;

use Ascetik\Hypothetik\Core\When;
use Ascetik\Hypothetik\Core\Maybe;
use PHPUnit\Framework\TestCase;

class TruthyConditionTest extends TestCase
{
    private When $boolean;

    protected function setUp(): void
    {
        $this->boolean = When::as(true);
    }

    public function testHypothetikBooleanValue()
    {
        $this->assertTrue($this->boolean->value());
    }

    public function testApplyingCallableOnBoolean()
    {
        $applied = $this->boolean->apply(fn () => 'all is true');
        $this->assertSame('all is true', $applied);
    }

    public function testApplyingCallableWithArguments()
    {
        $value = $this->boolean->apply(fn (string $name) => 'hello ' . $name, 'John');
        $this->assertSame('hello John', $value);
    }

    public function testShouldThenReturnAMaybeInstance()
    {
        $maybe = $this->boolean->then(fn () => 'truthy result');
        $this->assertInstanceOf(Maybe::class, $maybe);
        $this->assertSame('truthy result', $maybe->value());
    }

    public function testThenMethodShouldBeAbleToHandleArguments()
    {
        $maybe = $this->boolean->then(fn (string $name) => 'hello ' . $name, 'John');
        $this->assertSame('hello John', $maybe->value());

    }

    public function testConditionAlternativeValue()
    {
        $maybe = $this->boolean->then(fn () => 'truthy result')
            ->otherwise('falsy result');
        $this->assertSame('truthy result', $maybe->value());
    }

    public function testConditionWithEither()
    {
        $maybe = $this->boolean
            ->either(fn () => 'truthy condition')
            ->or(fn () => 'falsy condition')
            ->try();
        $this->assertSame('truthy condition', $maybe->value());
    }
}
