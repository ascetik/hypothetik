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

namespace Ascetik\Mono\Core;

use Ascetik\Callapsule\Factories\CallWrapper;
use Ascetik\Callapsule\Types\CallableType;
use Ascetik\Mono\Types\OptionnalValue;
use Throwable;

/**
 * Provide the ability to run
 * a callable according to  the
 * Option value of a Maybe instance
 *
 * @version 1.0.0
 */
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

    /**
     * Register a callable for a
     * null Maybe option value
     *
     * @param callable $function
     * @param mixed ...$arguments
     *
     * @return self
     */
    public function or(callable $function, mixed ...$arguments): self
    {
        if ($this->maybe->isNull()) {
            return self::use($this->maybe, $function, ...$arguments);
        }
        return $this;
    }

    /**
     * Register a Throwable instance
     * to throw on null Option value
     *
     * @param Throwable $thrown
     *
     * @return self
     */
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

    /**
     * Return a Maybe instance holding
     * the result of registered
     * callable execution
     *
     * @return Maybe
     */
    public function try(): Maybe
    {
        return Maybe::of($this->value());
    }

    /**
     * Return the result of registered
     * callable execution
     *
     * @return mixed
     */
    public function value(): mixed
    {
        return $this->call->apply($this->arguments);
    }

    public static function use(Maybe $maybe, callable $callable, mixed ...$arguments): self
    {
        return new self($maybe, CallWrapper::wrap($callable), ...$arguments);
    }
}
