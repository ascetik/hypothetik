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
 * Describe the behavior of a Maybe Option
 *
 * @version 1.0.0
 */
interface Option
{
    public function apply(callable $function, array $arguments = []): mixed;
    public function equals(self $option): bool;
    public function value(): mixed;
}
