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

namespace Ascetik\Hypothetik\Types;

use Ascetik\Hypothetik\Core\Either;

/**
 * Describe the behavior of a class able
 * to handle the validity of a value
 *
 * @version 1.0.0
 */
interface Hypothetik
{
    public function apply(callable $function, mixed ...$arguments): mixed;
    public function either(callable $function, mixed ...$arguments): Either;
    public function isValid(): bool;
    public function otherwise(mixed $value): self;
    public function then(callable $function, mixed ...$arguments): self;
    public function value(): mixed;

}
