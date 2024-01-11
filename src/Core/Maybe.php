<?php

/**
 * This is part of the ascetik/mono package
 *
 * @package    Mono\Core
 * @category   Core
 * @license    https://opensource.org/license/mit/  MIT License
 * @copyright  Copyright (c) 2023, Vidda
 * @author     Vidda <vidda@ascetik.fr>
 */

declare(strict_types=1);

namespace Ascetik\Mono\Core;

use Ascetik\Mono\Options\None;
use Ascetik\Mono\Options\Some;
use Ascetik\Mono\Types\Option;
use Ascetik\Callapsule\Types\CallableType;

/**
 * Library Core
 *
 * Hold an optionnal value in order
 * to avoid checks on an eventually null value.
 *
 * @template Generic
 * @version 0.1.0 (draft)
 */
final class Maybe
{
    /**
     * @param Option<Generic> $option
     */
    private function __construct(private Option $option)
    {
    }

    /**
     * Return a new value derived
     * from given function
     *
     * @param callable $function
     *
     * @return mixed
     */
    public function apply(callable $function, mixed ...$arguments): mixed
    {
        return $this->option->apply($function, $arguments);
    }

    public function either(callable $function): Either
    {
        return Either::use($this, $function, $this->value());
    }

    public function equals(self $value): bool
    {
        return $this->option->equals($value->option);
    }

    public function isNull(): bool
    {
        return is_null($this->value());
    }

    /**
     * @template Generic
     * @return self<Option<Generic>>
     */
    public function otherwise(mixed $value): self
    {
        return is_null($this->value())
            ? self::some($value)
            : $this;
    }

    /**
     * @template Generic
     * @return Generic
     */
    public function value(): mixed
    {
        return $this->option->value();
    }

    /**
     * @template Generic
     * @param Generic $value
     *
     * @return Maybe<Generic>
     */
    public function then(callable $function, mixed ...$arguments): self
    {
        return self::some($this->apply($function, ...$arguments));
    }

    /**
     * @return self<null>
     */
    public static function not(): self
    {
        return new self(new None());
    }

    public static function of(Option $option): self
    {
        return !is_null($option->value())
            ? new self($option)
            : self::not();
    }

    /**
     * @template Generic
     * @param Generic $value
     *
     * @return Maybe<Generic|null>
     */
    public static function some(mixed $value): self
    {
        if($value instanceof self)
        {
            return $value;
        }
        return self::of(Some::of($value));
    }
}
