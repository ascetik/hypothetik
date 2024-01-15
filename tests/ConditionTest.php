<?php

declare(strict_types=1);

namespace Ascetik\Hypothetik\Tests;

use Ascetik\Hypothetik\Core\Condition;
use PHPUnit\Framework\TestCase;

class ConditionTest extends TestCase
{
    public function testHypothetikTruthyBoolean()
    {
        $boolean = Condition::of(true);
        $this->assertTrue($boolean->value());
    }

    public function testHypothetikFalsyBoolean()
    {
        $boolean = Condition::of(false);
        $this->assertFalse($boolean->value());
    }

    public function testApplyOnTruthyBooleanShouldReturnMixedValue()
    {
        $boolean = Condition::of(true);
        $applied = $boolean->apply(fn () => 'all is true');
        $this->assertSame('all is true', $applied);
    }

    public function testApplyOnFalsyBooleanShouldReturnNull()
    {
        $boolean = Condition::of(false);
        $applied = $boolean->apply(fn () => 'all is true');
        $this->assertNull($applied);
    }


}
