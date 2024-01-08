<?php

declare(strict_types=1);

namespace Ascetik\Mono\Tests;

use Throwable;
use Ascetik\Mono\Core\Maybe;
use Ascetik\Mono\Core\Either;
use PHPUnit\Framework\TestCase;

class SomeOptionTest extends TestCase
{
    private Maybe $maybe;

    protected function setUp(): void
    {
        $this->maybe = Maybe::some('php');
    }

    public function testApplyingCallableOnSomeOption()
    {
        $uppercase = $this->maybe->apply(strtoupper(...));
        $this->assertIsString($uppercase);
        $this->assertSame('PHP', $uppercase);
    }

    public function testShouldApplyChangesOnNoneValue()
    {
        $uppercase = $this->maybe
            ->then(fn (string $value) => $value . ' is awesome')
            ->then(strtoupper(...));
        $this->assertInstanceOf(Maybe::class, $uppercase);
        // $this->assertFalse($this->maybe->equals($uppercase));
        $this->assertNotSame($this->maybe, $uppercase);
        $this->assertIsString($uppercase->value());
        $this->assertSame('PHP IS AWESOME', $uppercase->value());
    }


    public function testShouldDifferentiateAZeroFromANull()
    {
        $maybe = Maybe::of(0);
        $result = $maybe->then(fn (int $n) => $n + 3 * 0);
        $this->assertSame(0, $result->value());
        $result = $result->then(fn (int $n) => $n - 2);
        $this->assertSame(-2, $result->value());
    }

    public function testShouldNotUseDefaultValueOnSomeOption()
    {
        $this->assertSame($this->maybe, $this->maybe->otherwise('other value'));
    }


    public function testEitherImplementationWithTruthyOption()
    {
        $either = $this->maybe->either(strtoupper(...))
            ->or(fn (Throwable $thrown) => $thrown->getMessage());

        $this->assertInstanceOf(Either::class, $either);
        $this->assertSame('PHP', $either->value());
    }


    public function testEitherShouldReturnTruthyMaybe()
    {
        $try = $this->maybe->either(strtoupper(...))
            ->or(fn () => 'nothing')
            ->try();
        $this->assertInstanceOf(Maybe::class, $try);
        $this->assertSame('PHP', $try->value());
    }

    public function testShouldHaveNoneOptionOnNull()
    {
        $falsySome = Maybe::some(null)->otherwise('nothing');
        $this->assertInstanceOf(Maybe::class, $falsySome);
        $this->assertSame('nothing', $falsySome->value());
    }
}
