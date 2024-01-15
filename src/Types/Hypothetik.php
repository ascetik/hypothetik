<?php

declare(strict_types=1);

namespace Ascetik\Hypothetik\Types;

use Ascetik\Hypothetik\Core\Maybe;
use Ascetik\Hypothetik\Core\Either;

interface Hypothetik
{
    public function apply(callable $function, mixed ...$arguments): mixed;
    public function either(callable $function, mixed ...$arguments): Either;
    public function isValid(): bool;
    public function otherwise(mixed $value): self;
    public function then(callable $function, mixed ...$arguments): self;
    public function value(): mixed;

}
