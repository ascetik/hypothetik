<?php

declare(strict_types=1);

namespace Ascetik\Mono\Tests;

use Throwable;
use LogicException;
use Ascetik\Mono\Core\Maybe;
use Ascetik\Mono\Core\Either;
use PHPUnit\Framework\TestCase;

class NoneOptionTest extends TestCase
{
    private Maybe $maybe;

    protected function setUp(): void
    {
        $this->maybe = Maybe::none();
    }

    public function testTryingToApplyWithNoneOption()
    {
        $uppercase = $this->maybe->apply(strtoupper(...));
        $this->assertNull($uppercase);
    }

    public function testShouldUseDefaultOnNullOption()
    {
        $maybe = $this->maybe->otherwise('other value');
        $this->assertNotSame($maybe, $this->maybe);
        $this->assertSame('other value', $maybe->value());
    }

    public function testEitherImplementationWithFalsyOption()
    {
        $either = $this->maybe->either(strtoupper(...))
            ->or(fn () => 'failed');

        $this->assertInstanceOf(Either::class, $either);
        $this->assertSame('failed', $either->value());
    }

    public function testEitherWithFalsyValueReturningMaybeWithNewResult()
    {
        $try = $this->maybe->either(strtoupper(...))
            ->or(fn () => 'nothing')
            ->try();
        $this->assertInstanceOf(Maybe::class, $try);
        $this->assertSame('nothing', $try->value());
    }

    public function testEitherWithFalsyValueThrowingAnException()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('just a test');
        echo $this->maybe->either(strtoupper(...))
            ->or(
                function (Throwable $thrown) {
                    throw $thrown;
                },
                new LogicException('just a test')
            )
            ->value();
    }

    public function testEitherWithExceptionThrowShortCut()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('just a test');
        echo $this->maybe->either(strtoupper(...))
            ->orThrow(new LogicException('just a test'))
            ->value();
    }

}
