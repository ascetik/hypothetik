<?php

declare(strict_types=1);

namespace Ascetik\Hypothetik\Options;

use Ascetik\Hypothetik\Types\Option;

class Boolean implements Option
{
    public function __construct(private readonly bool $bool)
    {
    }

    public function apply(callable $function, array $arguments = []): mixed
    {
        return $this->bool
            ? call_user_func_array($function, $arguments)
            : null;
    }

    public function equals(Option $option): bool
    {
        return $option->value() === $this->bool;
    }

    public function value(): bool
    {
        return $this->bool;
    }
}
