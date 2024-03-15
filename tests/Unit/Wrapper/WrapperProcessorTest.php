<?php

declare(strict_types=1);

namespace Tests\Unit\Wrapper;

use DevZer0x00\CommandBus\Attribute\DoctrineTransactionalWrapper;
use DevZer0x00\CommandBus\Attribute\LockWrapper;
use DevZer0x00\CommandBus\HandlerInterface;
use DevZer0x00\CommandBus\Wrapper\HandlerWrapperFactoryInterface;
use DevZer0x00\CommandBus\Wrapper\WrapperProcessor;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Stubs\Handler\WrappedLockTransactionHandlerStub;
use Tests\Unit\Stubs\Handler\WrappedTransactionAttributeHandlerStub;

class WrapperProcessorTest extends TestCase
{
    public function testWithoutWrap()
    {
        $wrapperProcessor = new WrapperProcessor(
            wrapperFactoriesMap: []
        );

        $handler = $this->createMock(HandlerInterface::class);

        $this->assertSame($handler, $wrapperProcessor->wrap($handler));
    }

    public function testTransactionAttribute()
    {
        $transactionWrapperFactory = $this->createMock(HandlerWrapperFactoryInterface::class);
        $transactionWrapper = $this->createMock(HandlerInterface::class);
        $transactionWrapperFactory
            ->expects($this->once())
            ->method('factory')
            ->willReturn($transactionWrapper);

        $lockWrapperFactory = $this->createMock(HandlerWrapperFactoryInterface::class);
        $lockWrapper = $this->createMock(HandlerInterface::class);
        $lockWrapperFactory
            ->expects($this->never())
            ->method('factory')
            ->willReturn($lockWrapper);

        $wrapperProcessor = new WrapperProcessor(
            wrapperFactoriesMap: [
                LockWrapper::class => $lockWrapperFactory,
                DoctrineTransactionalWrapper::class => $transactionWrapperFactory,
            ]
        );

        $handler = new WrappedTransactionAttributeHandlerStub();

        $this->assertSame($transactionWrapper, $wrapperProcessor->wrap($handler));
    }

    public function testLockTransactionAttributeWithPriority1()
    {
        $transactionWrapperFactory = $this->createMock(HandlerWrapperFactoryInterface::class);
        $transactionWrapper = $this->createMock(HandlerInterface::class);
        $transactionWrapperFactory
            ->expects($this->once())
            ->method('factory')
            ->willReturn($transactionWrapper);

        $lockWrapperFactory = $this->createMock(HandlerWrapperFactoryInterface::class);
        $lockWrapper = $this->createMock(HandlerInterface::class);
        $lockWrapperFactory
            ->expects($this->once())
            ->method('factory')
            ->willReturn($lockWrapper);

        $wrapperProcessor = new WrapperProcessor(
            wrapperFactoriesMap: [
                DoctrineTransactionalWrapper::class => $transactionWrapperFactory,
                LockWrapper::class => $lockWrapperFactory,
            ]
        );

        $handler = new WrappedLockTransactionHandlerStub();

        $this->assertSame($lockWrapper, $wrapperProcessor->wrap($handler));
    }
}
