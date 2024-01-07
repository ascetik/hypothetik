<?php

declare(strict_types=1);

namespace Ascetik\Mono\Core;

use Closure;
use Throwable;

/**
 * @template Generic
 * @version 1.0.0
 */
class Maybe
{
    /**
     * @var Generic $value
     */
    public readonly mixed $value;
    private mixed $default = null;

    /**
     * @param Generic $value
     */
    private function __construct(mixed $value)
    {
        $this->value = $value;
    }

    public function map(Closure $function): self
    {
        $value = $this->value ?? $this->default;
        return is_null($value)
            ? $this
            : new self(call_user_func($function, $this->value));
    }

    // public function default(mixed $value):self
    // {
    //     $this->default = $value;
    //     return $this;
    // }
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @template Generic
     * @param Generic $default
     *
     * @return Generic
     */
    public function valueOrDefault(mixed $default): mixed
    {
        if (!is_null($this->value)) {
            /** @var Generic */
            return $this->value;
        }
        return $default;
    }

    public function doOrDie(Closure $success, Closure $failure)
    {
        try {
            return call_user_func($success, $this->value);
        } catch (Throwable $e) {
            return  call_user_func($failure, $e);
        }
    }

    public function valueOr(Closure $otherwise): mixed
    {
        try {
            return $this->value;
        } catch (Throwable $e) {
            echo 'catching' . PHP_EOL;
            echo $e->getMessage() . PHP_EOL;
            $result =  call_user_func($otherwise, $e);
        }
        echo $result . PHP_EOL;
        return $result;
    }

    public static function some(mixed $value): self
    {
        return new self($value);
    }

    public static function none(): self
    {
        return new self(null);
    }
}
