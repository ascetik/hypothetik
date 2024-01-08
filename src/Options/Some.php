<?php

declare(strict_types=1);

namespace Ascetik\Mono\Options;

use Closure;
use Ascetik\Mono\Types\Option;

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

    /**
     * @return Generic
     */
    public function value(): mixed
    {
        return $this->value;
    }

    public static function of(mixed $value)
    {
        if (is_null($value)) {
            return new None();
        }
        return new self($value);
    }
}
