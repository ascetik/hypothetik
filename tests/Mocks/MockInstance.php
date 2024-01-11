<?php

declare(strict_types=1);

namespace Ascetik\Mono\Tests\Mocks;

class MockInstance
{
    public function __construct(private ?string $value)
    {
    }

    public function concat(string $add):self
    {
        $this->value .= ' ' . $add;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function toUpperCase(): self
    {
        $this->value =  strtoupper($this->value);
        return $this;
    }

    public function erase(): self
    {
        $this->value = null;
        return $this;
    }
}
