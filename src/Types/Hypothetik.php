<?php

declare(strict_types=1);

namespace Ascetik\Hypothetik\Types;

use Ascetik\Hypothetik\Core\Maybe;

interface Hypothetik
{
    public function apply(callable $function, mixed ...$arguments): mixed;
    public function isValid(): bool;
    public function otherwise(mixed $value): self;
    public function then(callable $function, mixed ...$arguments): self;
    public function value(): mixed;

}
