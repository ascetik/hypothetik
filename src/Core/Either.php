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

use Ascetik\Callapsule\Factories\CallWrapper;
use Ascetik\Callapsule\Types\CallableType;
use Ascetik\Hypothetik\Types\Hypothetik;

/**
 * Provide the ability to run a function
 * for each Maybe possible Options
 *
 * @version 1.0.0
 */
final class Either
{
    private readonly array $arguments;

    private function __construct(
        private readonly Hypothetik $maybe,
        private readonly CallableType $call,
        mixed ...$arguments
    ) {
        $this->arguments = $arguments;
    }

    public function or(callable $function, mixed ...$arguments): self
    {
        if (!$this->maybe->isValid()) {
            return self::use($this->maybe, $function, ...$arguments);
        }
        return $this;
    }

    public function try(): Maybe
    {
        return Maybe::some($this->value());
    }

    public function value(): mixed
    {
        return $this->call->apply($this->arguments);
    }

    public static function use(Maybe $maybe, callable $call, mixed ...$arguments): self
    {

        return new self($maybe, CallWrapper::wrap($call), ...$arguments);
    }
}
