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

use Ascetik\Mono\Types\CallableRunningStrategy;

/**
 * Run a callable the common way...
 *
 * @version 1.0.0
 */
class MainCallStrategy implements CallableRunningStrategy
{
    public function call(callable $call, array $arguments = []): mixed
    {
        return call_user_func_array($call,$arguments);
    }
}
