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
 * This is a monad handling an
 * potential value in a fluent way
 * to limit null checks
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

    public function equals(Hypothetik $value): bool
    {
        return $this->option->equals($value->option);
    }

    public function isValid(): bool
    {
        return $this->option->isValid();
    }

    /**
     * @template Generic
     * @return self<Option<Generic>>
     */
    public function otherwise(mixed $value): self
    {
        return !$this->isValid()
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
     * @return Hypothetik<Generic>
     */
    public function then(callable $function, mixed ...$arguments): Hypothetik
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
        return new self($option);
    }

    /**
     * @template Generic
     * @param Generic $value
     *
     * @return Hypothetik<Generic>
     */
    public static function some(mixed $value): Hypothetik
    {
        return match (true) {
            $value instanceof Hypothetik => $value,
            is_bool($value) => When::ever($value),
            default => self::of(Some::of($value))
        };
    }
}
