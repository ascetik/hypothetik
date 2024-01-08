<?php

declare(strict_types=1);

namespace Ascetik\Mono\Options;

use Ascetik\Mono\Types\Option;
use Ascetik\Mono\Types\OptionnalValue;

class None implements Option
{
    public function apply(callable $function): null
    {
        return null;
    }

    public function value(): null
    {
        return null;
    }

    public function equals(Option $option): bool
    {
        return is_null($option->value());
    }
}
