<?php

/**
 * This is part of the ascetik/mono package
 *
 * @package    Mono\Core
 * @category   Data tranfer object
 * @license    https://opensource.org/license/mit/  MIT License
 * @copyright  Copyright (c) 2023, Vidda
 * @author     Vidda <vidda@ascetik.fr>
 */

declare(strict_types=1);

namespace Ascetik\Mono\Transfer;

use Ascetik\Callapsule\Factories\CallWrapper;
use Ascetik\Callapsule\Types\CallableType;
use Ascetik\Mono\Types\CallableRunningStrategy;

/**
 * Hold a callable and a runner
 * to run the callable.
 *
 * This transfer object is a bridge between Maybe and Either
 * to transfer the function call strategy to the Either instance.
 *
 * @version 1.0.0
 */
class EitherCall
{
    public function __construct(
        private CallableRunningStrategy $runner,
        private CallableType $call
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

    public static function create(CallableRunningStrategy $runner, callable $callable): self
    {
        return new self($runner, CallWrapper::wrap($callable));
    }
}
