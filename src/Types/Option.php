<?php

declare(strict_types=1);

namespace Ascetik\Mono\Types;


interface Option extends OptionnalValue
{
    public function apply(callable $function): mixed;
    public function equals(self $option): bool;
}
