<?php

declare(strict_types=1);

namespace Ascetik\Mono\Core;

use Ascetik\Mono\Options\None;
use Ascetik\Mono\Options\Some;
use Ascetik\Mono\Types\Option;
use Ascetik\Mono\Types\OptionnalValue;

/**
 * @template Generic
 * @version 1.0.0
 */
class Maybe implements OptionnalValue
{
    /**
     * @param Option<Generic> $option
     */
    private function __construct(private Option $option)
    {
    }

    public function apply(callable $function): mixed
    {
        return $this->option->apply($function);
    }

    public function either(callable $function): Either
    {
        return Either::use($this, $function, $this->value());
    }

    /**
     * @template Generic
     * @param Generic $value
     *
     * @return self<Option<Generic>>
     */
    public function otherwise(mixed $value): self
    {
        return is_null($this->value())
            ? self::some($value)
            : $this;
    }

    public function isNull(): bool
    {
        return is_null($this->value());
    }

    /**
     * @return Generic
     */
    public function value(): mixed
    {
        return $this->option->value();
    }

    public function then(callable $function): self
    {
        return self::of($this->apply($function));
    }

    public static function some(mixed $value): self
    {
        return new self(Some::of($value));
    }

    public static function none(): self
    {
        return new self(new None());
    }

    public static function of(mixed $value): self
    {
        return is_null($value)
            ? self::none()
            : self::some($value);
    }
}
