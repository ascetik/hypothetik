<?php

/**
 * This is part of the ascetik/mono package
 *
 * @package    Mono\Core
 * @category   Interface
 * @license    https://opensource.org/license/mit/  MIT License
 * @copyright  Copyright (c) 2023, Vidda
 * @author     Vidda <vidda@ascetik.fr>
 */

declare(strict_types=1);

namespace Ascetik\Mono\Types;

/**
 * Describe the behavior of an instance
 * responsible for callable execution
 *
 * @version 1.0.0
 */
interface CallableRunningStrategy
{
    public function call(callable $call, array $arguments = []): mixed;
}
