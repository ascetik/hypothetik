<?php

declare(strict_types=1);

namespace Ascetik\Hypothetik\Tests;

use Ascetik\Hypothetik\Core\Boolean;
use PHPUnit\Framework\TestCase;

class BooleanTest extends TestCase
{
    public function testHypothetikBoolean()
    {
        $boolean = Boolean::of(true);
        $this->assertTrue($boolean->value());
    }
}
