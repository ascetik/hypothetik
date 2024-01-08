<?php

declare(strict_types=1);

namespace Ascetik\Mono\Types;

use Closure;

interface Option
{
    // public function map(Closure $function): self;
    public function value(): mixed;
    public function apply(callable $function):mixed;
}
