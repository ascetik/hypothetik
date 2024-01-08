<?php

declare(strict_types=1);

namespace Ascetik\Mono\Core;

use Ascetik\Mono\Options\None;
use Ascetik\Mono\Options\Some;
use Ascetik\Mono\Types\Option;

/**
 * @template Generic
 * @version 1.0.0
 */
class Maybe
{
    /**
     * @param Option $option
     */
    private function __construct(private Option $option)
    {
    }

    // je récupère la valeur finale de ma fonction
    public function apply(callable $function): mixed
    {
        return $this->option->apply($function);
    }

    public function either(callable $function): Either
    {
        return Either::create($this, $function, $this->value());
    }

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

    public function value(): mixed
    {
        return $this->option->value();
    }


    /**
     * je récupère un Maybe du résultat de ma fonction
     * flatMap c'est trop technique
     *
     * on cherche ici à modifier un Maybe à l'aide d'une fonction.
     */
    public function from(callable $function): self
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
