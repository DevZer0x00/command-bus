<?php

declare(strict_types=1);

namespace Tests\unit\Wrapper;

use DevZer0x00\CommandBus\Attribute\TransactionalWrapper;
use DevZer0x00\CommandBus\CommandHandlerInterface;
use DevZer0x00\CommandBus\Wrapper\HandlerWrapperFactoryInterface;
use DevZer0x00\CommandBus\Wrapper\HandlerWrapperInterface;
use DevZer0x00\CommandBus\Wrapper\NopHandlerWrapper;
use DevZer0x00\CommandBus\Wrapper\WrapperProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Tests\unit\Stubs\WrappedTransactionAttributeHandlerStub;

class WrapperProcessorTest extends TestCase
{
    private ContainerInterface|MockObject $container;

    protected function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
    }

    public function testWithoutWrap()
    {
        $wrapperProcessor = new WrapperProcessor(
            container: $this->container,
            wrapperFactoriesMap: []
        );

        $handler = $this->createMock(CommandHandlerInterface::class);

        $this->assertInstanceOf(NopHandlerWrapper::class, $wrapperProcessor->wrap($handler));
    }

    public function testTransactionAttribute()
    {
        $wrapperFactory = $this->createMock(HandlerWrapperFactoryInterface::class);

        $wrapper = $this->createMock(HandlerWrapperInterface::class);
        $wrapperFactory
            ->expects($this->once())
            ->method('factory')
            ->willReturn($wrapper);

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with($wrapperFactory::class)
            ->willReturn($wrapperFactory);

        $wrapperProcessor = new WrapperProcessor(
            container: $this->container,
            wrapperFactoriesMap: [
                TransactionalWrapper::class => $wrapperFactory::class,
            ]
        );

        $handler = new WrappedTransactionAttributeHandlerStub();

        $this->assertSame($wrapper, $wrapperProcessor->wrap($handler));
    }
}
