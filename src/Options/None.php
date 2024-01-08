<?php

declare(strict_types=1);

namespace Ascetik\Mono\Options;

use Ascetik\Mono\Types\Option;
use Closure;

class None implements Option
{
    public function map(Closure $function): Option
    {
        return $this;
    }

    public function value(): null
    {
        return null;
    }

    public function apply(callable $function): mixed
    {
        return null;
    }
    // public function otherwise(mixed $value): Option
    // {
    //     return Maybe::of($value);
    // }
}
