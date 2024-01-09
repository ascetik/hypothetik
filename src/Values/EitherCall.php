<?php

declare(strict_types=1);

namespace Ascetik\Mono\Values;

use Ascetik\Callapsule\Factories\CallWrapper;
use Ascetik\Callapsule\Types\CallableType;
use Ascetik\Mono\Types\CallStrategy;

class EitherCall
{
    public function __construct(
        public readonly CallStrategy $runner,
        public readonly CallableType $call
    ) {
    }

    public function run(array $arguments = []): mixed
    {
        return $this->runner->call($this->call->action(), $arguments);
    }

    public function with(callable $callable): self
    {
        return self::create($this->runner, $callable);
    }

    public static function create(CallStrategy $runner, callable $callable): self
    {
        return new self($runner, CallWrapper::wrap($callable));
    }
}
