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

namespace Ascetik\Hypothetik\Core;

use Ascetik\Hypothetik\Options\None;
use Ascetik\Hypothetik\Options\Some;
use Ascetik\Hypothetik\Types\Hypothetik;
use Ascetik\Hypothetik\Types\Option;


/**
 * Library Core
 *
 * Hold an optionnal value in order
 * to avoid checks on an eventually null value.
 *
 * @template Generic
 * @version 0.1.0 (draft)
 */
final class Maybe implements Hypothetik
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

    public function either(callable $function, mixed ...$arguments): Either
    {
        return Either::useContext($this, $function, ...[$this->value(), ...$arguments]);
    }

    public function equals(self $value): bool
    {
        return $this->option->equals($value->option);
    }

    public function isValid(): bool
    {
        return !is_null($this->value());
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
    public static function some(mixed $value): Hypothetik
    {
        if ($value instanceof self) {
            return $value;
        }
        if (is_bool($value)) {
            return Condition::as($value);
        }
        return self::of(Some::of($value));
    }
}
