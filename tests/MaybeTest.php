<?php

declare(strict_types=1);

namespace Ascetik\Mono\Tests;

use Ascetik\Mono\Core\Maybe;
use PHPUnit\Framework\TestCase;
use Throwable;

class MaybeTest extends TestCase
{
    public function testMaybeHavingValue()
    {
        $maybe = Maybe::some('test');
        $result = $maybe->valueOrDefault(
            'nothing'
            // fn (string $value) => $value,
            // fn (Throwable $e) => $e
        );

        $this->assertSame('test', $result);
    }

    public function testMaybeWithNoValue()
    {
        $maybe = Maybe::none();
        $result = $maybe->valueOrDefault(
            'nothing'
            // fn (string $value) => $value,
            // fn (Throwable $e) => $e
        );

        $this->assertSame('nothing', $result);

    }

    public function testMaybeWithNullValue()
    {
        $maybe = Maybe::none();
        $result = $maybe->valueOr(
            fn (Throwable $e) => 'error '.$e->getMessage()
        );
        echo 'result : '. $result;
        $this->assertTrue(true);
    }
}
