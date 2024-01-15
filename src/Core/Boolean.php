<?php

declare(strict_types=1);

namespace Ascetik\Hypothetik\Core;

use Ascetik\Hypothetik\Options\Some;
use Ascetik\Hypothetik\Types\Hypothetik;

class Boolean implements Hypothetik
{
    /**
     * @param Some<bool> $bool
     */
    private function __construct(private Some $bool)
    {

    }

    public static function of(bool $bool)
    {
        return new self(Some::of($bool));
    }

    public function value(): mixed
    {
        return $this->bool->value();
    }

}
