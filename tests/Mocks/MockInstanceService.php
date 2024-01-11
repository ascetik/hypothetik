<?php

declare(strict_types=1);

namespace Ascetik\Hypothetik\Tests\Mocks;

class MockInstanceService
{
    public function get(MockInstance $instance): ?string
    {
        return $instance->getValue();
    }

    public function add(MockInstance $instance)
    {
        return $instance->concat('handled by InstanceService');
    }

    public function append(MockInstance $instance, string $add)
    {
        return $instance->concat('handled '.$add);

    }
}
