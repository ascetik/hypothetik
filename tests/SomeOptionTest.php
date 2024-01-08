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
        $this->maybe = Maybe::some('value');
    }

    public function testApplyingMutationWithSomeOption()
    {
        $uppercase = $this->maybe->apply(strtoupper(...));
        $this->assertIsString($uppercase);
        $this->assertSame('VALUE', $uppercase);
    }

    public function testShouldApplyChangesAndReturnValue()
    {
        $uppercase = $this->maybe->from(strtoupper(...));
        $this->assertInstanceOf(Maybe::class, $uppercase);
        $this->assertNotSame($this->maybe, $uppercase);
        $this->assertIsString($uppercase->value());
        $this->assertSame('VALUE', $uppercase->value());
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
        $this->assertSame('VALUE', $either->value());
    }


    public function testEitherShouldReturnTruthyMaybe()
    {
        $try = $this->maybe->either(strtoupper(...))
            ->or(fn () => 'nothing')
            ->try();
        $this->assertInstanceOf(Maybe::class, $try);
        $this->assertSame('VALUE', $try->value());
    }

}
