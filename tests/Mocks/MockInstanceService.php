<?php

declare(strict_types=1);

namespace Ascetik\Mono\Tests\Mocks;

class MockInstanceService
{
    public function get(MockInstance $instance): ?string
    {
        return $instance->getValue();
    }
}
