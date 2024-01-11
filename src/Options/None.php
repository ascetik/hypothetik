<?php

/**
 * This is part of the ascetik/mono package
 *
 * @package    Mono\Core
 * @category   Option implementation
 * @license    https://opensource.org/license/mit/  MIT License
 * @copyright  Copyright (c) 2023, Vidda
 * @author     Vidda <vidda@ascetik.fr>
 */

declare(strict_types=1);

namespace Ascetik\Mono\Options;

use Ascetik\Mono\Types\Option;

/**
 * Option with a null value
 *
 * @version 1.0.0
 */
final class None implements Option
{
    public function apply(callable $function, array $arguments = []): null
    {
        return null;
    }

    public function value(): null
    {
        return null;
    }

    public function equals(Option $option): bool
    {
        return is_null($option->value());
    }
}
