<?php

declare(strict_types=1);

namespace Ascetik\Hypothetik\Tests;

use Ascetik\Hypothetik\Core\Maybe;
use Ascetik\Hypothetik\Tests\Mocks\MockInstance;
use PHPUnit\Framework\TestCase;

class SomeInstanceTest extends TestCase
{
    private Maybe $maybe;

    protected function setUp(): void
    {
        $instance = new MockInstance('test');
        $maybe = Maybe::some($instance);
        $this->maybe = $maybe;
    }

    public function testMaybeContainsAnInstance()
    {
        $value = $this->maybe->value();
        $this->assertInstanceOf(MockInstance::class, $value);
        $this->assertSame('test', $value->getValue());
    }

    public function testShouldApplyFunctionWithInstanceMethods()
    {
        $result = $this->maybe->apply(fn (MockInstance $instance) => $instance->concat('with concat method'));
        $this->assertInstanceOf(MockInstance::class, $result);
        $this->assertSame('test with concat method', $result->getValue());
    }

    public function testShouldApplyAFunctionAndReturnATruthyMaybe()
    {
        $result = $this->maybe->then(fn (MockInstance $instance) => $instance->concat('with concat method'));
        $this->assertInstanceOf(MockInstance::class, $result->value());
        $this->assertSame('test with concat method', $result->value()->getValue());
    }

    public function testChainOfThenMethods()
    {
        $result = $this->maybe
            ->then(fn (MockInstance $instance) => $instance->toUpperCase())
            ->then(fn (MockInstance $instance) => $instance->getValue())
            ->then(fn (string $value) => $value .= ' of chained mutations');
        $this->assertSame('TEST of chained mutations', $result->value());
    }

    public function testOtherwiseMethodOnTruthyMaybeIsNotApplied()
    {
        $result = $this->maybe
            ->then(fn (MockInstance $instance) => $instance->toUpperCase())
            ->then(fn (MockInstance $instance) => $instance->getValue())
            ->otherwise('no instance found')
            ->then(fn (string $value) => $value . ' for this session');
        $this->assertSame('TEST for this session', $result->value());
    }

    public function testOtherwiseMethodOnFalsyMaybeIsApplied()
    {
        $result = $this->maybe
            ->then(fn (MockInstance $instance) => $instance->erase())
            ->then(fn (MockInstance $instance) => $instance->getValue())
            ->otherwise('no instance found')
            ->then(fn (string $value) => $value . ' for this session');
        $this->assertSame('no instance found for this session', $result->value());
    }

    public function testEitherFunctionnalityWithAnInstance()
    {
        $result = $this->maybe
            ->either(fn (MockInstance $instance) => $instance->toUpperCase())
            ->or(fn()=>new MockInstance('alternative test'))
            ->try()
            ->then(fn(MockInstance $instance) => $instance->concat('for Either use'))
            ->value();
            $this->assertSame('TEST for Either use',$result->getValue());
    }
}
