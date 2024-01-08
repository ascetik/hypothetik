<?php

declare(strict_types=1);

namespace Ascetik\Mono\Options;

use Closure;
use Ascetik\Mono\Types\Option;
use Ascetik\Mono\Types\OptionnalValue;

/**
 * @template Generic
 *
 * @version 1.0.0
 */
class Some implements Option
{
    /**
     * @template Generic
     *
     * @param Generic $value
     */
    private function __construct(public readonly mixed $value)
    {
    }

    public function apply(callable $function): mixed
    {
        return call_user_func($function, $this->value);
    }

    public function equals(Option $option): bool
    {
        return $this->value === $option->value;
    }
    /**
     * @return Generic
     */
    public function value(): mixed
    {
        return $this->value;
    }

    /**
     * @template Generic
     * @param Generic $value
     *
     * @return Option<Generic>
     */
    public static function of(mixed $value): Option
    {
        if (is_null($value)) {
            return new None();
        }
        return new self($value);
    }
}
