<?php

declare(strict_types=1);

namespace Ascetik\Mono\Transfer;

use Ascetik\Mono\Types\CallStrategy;

class MainCallStrategy implements CallStrategy
{
    public function call(callable $call, array $arguments = []): mixed
    {
        return call_user_func_array($call,$arguments);
    }
}
