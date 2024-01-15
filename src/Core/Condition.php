<?php

declare(strict_types=1);

namespace Ascetik\Hypothetik\Core;

use Ascetik\Hypothetik\Options\Some;
use Ascetik\Hypothetik\Types\Hypothetik;

class Condition implements Hypothetik
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

    public function apply(callable $function, mixed ...$arguments): mixed
    {
        if ($this->value()) {
            return call_user_func_array($function, $arguments);
        }
        return null;
    }


    public function otherwise(mixed $value): Hypothetik
    {
        return !$this->value()
            ? Maybe::some($value)
            : $this;
    }

    public function then(callable $function, mixed ...$arguments): Hypothetik
    {
        /**
         * Si je suis à true, j'execute la fonction qui m'est donnée et j'en retourne le résultat dans un Maybe
         * Si je suis à false,
         *
         * Admettons que je cherche ma route parmi les routes enregistrées.
         * si la route courante ne correspond pas, je me retrouve avec un Condition<false>
         * et pour chaque route, je veux
         */
        return  Maybe::some($this->apply($function, ...$arguments));
        // if ($this->value()) {
        //     return Maybe::some($this->apply($function, ...$arguments));
        // }
        // return Maybe::not();
    }

    public function value(): mixed
    {
        return $this->bool->value();
    }
}
