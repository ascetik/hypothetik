<?php

/**
 * This is part of the ascetik/mono package
 *
 * @package    Mono\Core
 * @category   Option implementation
 * @license    https://opensource.org/license/mit/  MIT License
 * @copyright  Copyright (c) 2023, Vidda
 * @author     Vidda <vidda@ascetik.fr>
 */

declare(strict_types=1);

namespace Ascetik\Hypothetik\Options;

use Ascetik\Hypothetik\Types\Option;

/**
 * Option with a not null value
 *
 * @template Generic
 *
 * @version 1.0.0
 */
final class Some implements Option
{
    /**
     * @template Generic
     *
     * @param Generic $value
     */
    private function __construct(public readonly mixed $value)
    {
    }

    public function apply(callable $function, array $arguments = []): mixed
    {
        return call_user_func_array($function, [$this->value, ...$arguments]);
    }

    public function equals(Option $option): bool
    {
        return $this->value === $option->value;
    }

    /**
     * @template Generic
     *
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
     * @return Option<Generic|null>
     */
    public static function of(mixed $value): Option
    {
        return match (true) {
            $value instanceof Option => $value,
            is_null($value) => new None(),
            default => new self($value)
        };
    }
}
