<?php

declare(strict_types=1);

namespace Ascetik\Mono\Tests;

use Ascetik\Mono\Core\Maybe;
use PHPUnit\Framework\TestCase;
use Ascetik\Mono\Tests\Mocks\MockInstance;
use Ascetik\Mono\Tests\Mocks\MockInstanceService;
use Ascetik\Mono\Tests\Mocks\MockInvokableInstanceMutator;

class InstanceMethodAsCallableTest extends TestCase
{
    /**
     * @var Maybe<MockInstance>
     */
    private Maybe $maybe;
    private MockInstanceService $service;

    protected function setUp(): void
    {
        $this->service = new MockInstanceService();
        $instance = new MockInstance('test');
        $this->maybe = Maybe::some($instance);
    }

    public function testShouldApplyServiceMethodOnInstance()
    {
        $result = $this->maybe->apply($this->service->add(...));
        $this->assertInstanceOf(MockInstance::class, $result);
        $this->assertSame('test handled by InstanceService', $result->getValue());
    }

    public function testShouldApplyServiceMethodOnInstanceAsArray()
    {
        $result = $this->maybe->apply([$this->service,'add']);
        $this->assertInstanceOf(MockInstance::class, $result);
        $this->assertSame('test handled by InstanceService', $result->getValue());
    }

    public function testShouldApplyMutationUsingAnInvokableInstance()
    {
        $invokable = new MockInvokableInstanceMutator();
        $result = $this->maybe->apply($invokable);
        $this->assertInstanceOf(MockInstance::class, $result);
        $this->assertSame('test handled by InvokableInstanceMutator', $result->getValue());
    }
}
