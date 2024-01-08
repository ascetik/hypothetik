<?php

declare(strict_types=1);

namespace Ascetik\Mono\Core;

use Ascetik\Callapsule\Factories\CallWrapper;
use Ascetik\Callapsule\Types\CallableType;
use Throwable;

class Either
{
    private readonly array $arguments;

    private function __construct(
        private Maybe $maybe,
        private CallableType $call,
        mixed ...$arguments
    ) {
        $this->arguments = $arguments;
    }

    public function or(callable $function, mixed ...$arguments)
    {
        if ($this->maybe->isNull()) {
            return self::create($this->maybe, $function, ...$arguments);
        }
        return $this;
    }

    public function orThrow(Throwable $thrown)
    {
        return self::create(
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

    public static function create(Maybe $maybe, callable $callable, mixed ...$arguments): self
    {
        return new self($maybe, CallWrapper::wrap($callable), ...$arguments);
    }
}
