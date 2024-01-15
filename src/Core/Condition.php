<?php

declare(strict_types=1);

namespace Ascetik\Hypothetik\Core;

use Ascetik\Hypothetik\Options\Some;
use Ascetik\Hypothetik\Types\Hypothetik;

class Condition implements Hypothetik
{
    /**
     * @param Some<bool> $bool
     */
    private function __construct(private Some $bool)
    {
    }

    public static function of(bool $bool)
    {
        return new self(Some::of($bool));
    }

    public function apply(callable $function, mixed ...$arguments): mixed
    {
        return $this->value()
            ? call_user_func_array($function, $arguments)
            : null;
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
