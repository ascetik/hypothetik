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

use Ascetik\Hypothetik\Options\Some;
use Ascetik\Hypothetik\Types\Hypothetik;

/**
 * Handle hypothetik boolean value
 *
 * This is a monad handling a
 * boolean value in a fluent way
 * to limit "if" expressions usage
 *
 * @version 1.0.0
 */
class When implements Hypothetik
{
    /**
     * @param Some<bool> $bool
     */
    private function __construct(private Some $bool)
    {
    }

    public static function as(bool $bool): self
    {
        return new self(Some::of($bool));
    }

    public function apply(callable $function, mixed ...$arguments): mixed
    {
        return $this->value()
            ? call_user_func_array($function, $arguments)
            : null;
    }

    public function either(callable $function, mixed ...$arguments): Either
    {
        return Either::useContext($this, $function, ...$arguments);
    }

    public function isValid(): bool
    {
        return $this->bool->value();
    }

    public function otherwise(mixed $value): Hypothetik
    {
        return !$this->value()
            ? Maybe::some($value)
            : $this;
    }

    public function then(callable $function, mixed ...$arguments): Hypothetik
    {
        return  Maybe::some($this->apply($function, ...$arguments));
    }

    public function value(): mixed
    {
        return $this->bool->value();
    }
}
