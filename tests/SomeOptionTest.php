<?php

declare(strict_types=1);

namespace Ascetik\Hypothetik\Tests;

use Throwable;
use Ascetik\Hypothetik\Core\Maybe;
use Ascetik\Hypothetik\Core\Either;
use PHPUnit\Framework\TestCase;

class SomeOptionTest extends TestCase
{
    private Maybe $maybe;

    protected function setUp(): void
    {
        $maybe = Maybe::some('php');
        $this->maybe = $maybe;
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
        $this->assertFalse($this->maybe->equals($uppercase));
        $value = $uppercase->value();
        $this->assertIsString($value);
        $this->assertSame('PHP IS AWESOME', $uppercase->value());
    }

    public function testShouldDifferentiateAZeroFromANull()
    {
        $maybe = Maybe::some(0);
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

    public function testShouldNowAcceptMoreArgumentsInApplyCallable()
    {
        $uppercase = $this->maybe
            ->then(fn (string $value, string $add) => $value . ' ' . $add, 'is awesome')
            ->then(strtoupper(...));
        $this->assertSame('PHP IS AWESOME', $uppercase->value());
    }

    public function testMaybeCannotHoldAnotherMaybe()
    {
        $otherMaybe = Maybe::some($this->maybe);
        $this->assertIsString($otherMaybe->value());
        $this->assertSame('php', $otherMaybe->value());
    }

    public function testASomeInstanceCannotHoldAnotherOption()
    {
        $otherMaybe = Maybe::some(Maybe::some('other'));
        $this->assertIsString($otherMaybe->value());
    }

    public function testASomeInstanceCannotHoldANoneOption()
    {
        $otherMaybe = Maybe::some(Maybe::not());
        $this->assertNull($otherMaybe->value());
    }

    public function testMaybeContainingAClosure()
    {
        $maybe = Maybe::some(fn (string $subject) => 'test for ' . $subject);
        $this->assertSame('test for closure', call_user_func($maybe->value(), 'closure'));
    }

    public function testAnotherOneWithArgument()
    {
        $maybe = Maybe::some('/about');
        $result = $maybe->apply(trim(...), '/');
        $this->assertSame('about', $result);
    }

    public function testAnotherAgainWithTwoArguments()
    {
        $pathToAboutPage = Maybe::some('/about');
        $function = fn (string $value, string $separator, string $add) => trim($value, $separator) . '-' . $add;
        $this->assertSame('about-page', $pathToAboutPage->apply($function, '/', 'page'));

    }
}
