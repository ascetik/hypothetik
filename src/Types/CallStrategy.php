<?php

declare(strict_types=1);

namespace Ascetik\Mono\Types;

interface CallStrategy
{
    public function call(callable $call, array $arguments = []): mixed;
}
