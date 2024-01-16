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

/**
 * Provide value validity check
 * and raw value output
 *
 * @version 1.0.0
 */
interface OptionnalValue
{
    public function isValid(): bool;
    public function value(): mixed;
}
