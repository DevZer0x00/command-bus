<?php

declare(strict_types=1);

namespace Tests\unit\Wrapper;

use DevZer0x00\CommandBus\Attribute\LockWrapper;
use DevZer0x00\CommandBus\Attribute\TransactionalWrapper;
use DevZer0x00\CommandBus\CommandHandlerInterface;
use DevZer0x00\CommandBus\Wrapper\HandlerWrapperFactoryInterface;
use DevZer0x00\CommandBus\Wrapper\HandlerWrapperInterface;
use DevZer0x00\CommandBus\Wrapper\NopHandlerWrapper;
use DevZer0x00\CommandBus\Wrapper\WrapperProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Tests\unit\Stubs\WrappedLockTransactionHandlerStub;
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
        $transactionWrapperFactory = $this->createMock(HandlerWrapperFactoryInterface::class);
        $transactionWrapper = $this->createMock(HandlerWrapperInterface::class);
        $transactionWrapperFactory
            ->expects($this->once())
            ->method('factory')
            ->willReturn($transactionWrapper);

        $lockWrapperFactory = $this->createMock(HandlerWrapperFactoryInterface::class);
        $lockWrapper = $this->createMock(HandlerWrapperInterface::class);
        $lockWrapperFactory
            ->expects($this->never())
            ->method('factory')
            ->willReturn($lockWrapper);

        $this->container
            ->expects($this->once())
            ->method('get')
            ->willReturnCallback(function ($arg) use ($lockWrapperFactory, $transactionWrapperFactory) {
                $factories = [
                    'l' => $lockWrapperFactory,
                    't' => $transactionWrapperFactory,
                ];

                return $factories[$arg];
            });

        $wrapperProcessor = new WrapperProcessor(
            container: $this->container,
            wrapperFactoriesMap: [
                TransactionalWrapper::class => 't',
                LockWrapper::class => 'l',
            ]
        );

        $handler = new WrappedTransactionAttributeHandlerStub();

        $this->assertSame($transactionWrapper, $wrapperProcessor->wrap($handler));
    }

    public function testLockTransactionAttributeWithPriority1()
    {
        $transactionWrapperFactory = $this->createMock(HandlerWrapperFactoryInterface::class);
        $transactionWrapper = $this->createMock(HandlerWrapperInterface::class);
        $transactionWrapperFactory
            ->expects($this->once())
            ->method('factory')
            ->willReturn($transactionWrapper);

        $lockWrapperFactory = $this->createMock(HandlerWrapperFactoryInterface::class);
        $lockWrapper = $this->createMock(HandlerWrapperInterface::class);
        $lockWrapperFactory
            ->expects($this->once())
            ->method('factory')
            ->willReturn($lockWrapper);

        $this->container
            ->expects($this->atLeast(2))
            ->method('get')
            ->willReturnCallback(function ($arg) use ($lockWrapperFactory, $transactionWrapperFactory) {
                $factories = [
                    'l' => $lockWrapperFactory,
                    't' => $transactionWrapperFactory,
                ];

                return $factories[$arg];
            });

        $wrapperProcessor = new WrapperProcessor(
            container: $this->container,
            wrapperFactoriesMap: [
                TransactionalWrapper::class => 't',
                LockWrapper::class => 'l',
            ]
        );

        $handler = new WrappedLockTransactionHandlerStub();

        $this->assertSame($lockWrapper, $wrapperProcessor->wrap($handler));
    }
}
