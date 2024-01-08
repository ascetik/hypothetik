<?php

declare(strict_types=1);

namespace Ascetik\Mono\Core;

use Ascetik\Callapsule\Factories\CallWrapper;
use Ascetik\Callapsule\Types\CallableType;
use Ascetik\Mono\Types\OptionnalValue;
use Throwable;

class Either implements OptionnalValue
{
    private readonly array $arguments;

    private function __construct(
        private readonly Maybe $maybe,
        private readonly CallableType $call,
        mixed ...$arguments
    ) {
        $this->arguments = $arguments;
    }

    public function or(callable $function, mixed ...$arguments): self
    {
        if ($this->maybe->isNull()) {
            return self::use($this->maybe, $function, ...$arguments);
        }
        return $this;
    }

    public function orCatch(Throwable $thrown): self
    {
        return self::use(
            Maybe::none(),
            function (Throwable $e) {
                throw $e;
            },
            $thrown
        );
    }

    public function try(): Maybe
    {
        return Maybe::of($this->value());
    }

    public function value(): mixed
    {
        return $this->call->apply($this->arguments);
    }

    public static function use(Maybe $maybe, callable $callable, mixed ...$arguments): self
    {
        return new self($maybe, CallWrapper::wrap($callable), ...$arguments);
    }
}
