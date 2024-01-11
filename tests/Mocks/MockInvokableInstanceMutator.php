<?php

declare(strict_types=1);

namespace Ascetik\Hypothetik\Tests\Mocks;

class MockInvokableInstanceMutator
{
    public function __invoke(MockInstance $instance): MockInstance
    {
        return $instance->concat('handled by InvokableInstanceMutator');
    }
}
