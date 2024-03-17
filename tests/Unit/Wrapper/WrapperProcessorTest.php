<?php

declare(strict_types=1);

namespace Tests\Unit\Wrapper;

use DevZer0x00\CommandBus\Attribute\DoctrineORMTransactionalWrapper;
use DevZer0x00\CommandBus\Attribute\LockWrapper;
use DevZer0x00\CommandBus\CommandHandlerInterface;
use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperFactoryInterface;
use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperInterface;
use DevZer0x00\CommandBus\Wrapper\NopCommandHandlerWrapper;
use DevZer0x00\CommandBus\Wrapper\WrapperProcessor;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Stubs\Handler\WrappedLockTransactionCommandHandlerStub;
use Tests\Unit\Stubs\Handler\WrappedTransactionAttributeCommandHandlerStub;

class WrapperProcessorTest extends TestCase
{
    public function testWithoutWrap()
    {
        $wrapperProcessor = new WrapperProcessor(
            wrapperFactoriesMap: []
        );

        $handler = $this->createMock(CommandHandlerInterface::class);

        $this->assertInstanceOf(NopCommandHandlerWrapper::class, $wrapperProcessor->wrap($handler));
    }

    public function testTransactionAttribute()
    {
        $transactionWrapperFactory = $this->createMock(CommandHandlerWrapperFactoryInterface::class);
        $transactionWrapper = $this->createMock(CommandHandlerWrapperInterface::class);
        $transactionWrapperFactory
            ->expects($this->once())
            ->method('factory')
            ->willReturn($transactionWrapper);

        $lockWrapperFactory = $this->createMock(CommandHandlerWrapperFactoryInterface::class);
        $lockWrapper = $this->createMock(CommandHandlerWrapperInterface::class);
        $lockWrapperFactory
            ->expects($this->never())
            ->method('factory')
            ->willReturn($lockWrapper);

        $wrapperProcessor = new WrapperProcessor(
            wrapperFactoriesMap: [
                LockWrapper::class => $lockWrapperFactory,
                DoctrineORMTransactionalWrapper::class => $transactionWrapperFactory,
            ]
        );

        $handler = new WrappedTransactionAttributeCommandHandlerStub();

        $this->assertSame($transactionWrapper, $wrapperProcessor->wrap($handler));
    }

    public function testLockTransactionAttributeWithPriority1()
    {
        $transactionWrapperFactory = $this->createMock(CommandHandlerWrapperFactoryInterface::class);
        $transactionWrapper = $this->createMock(CommandHandlerWrapperInterface::class);
        $transactionWrapperFactory
            ->expects($this->once())
            ->method('factory')
            ->willReturn($transactionWrapper);

        $lockWrapperFactory = $this->createMock(CommandHandlerWrapperFactoryInterface::class);
        $lockWrapper = $this->createMock(CommandHandlerWrapperInterface::class);
        $lockWrapperFactory
            ->expects($this->once())
            ->method('factory')
            ->willReturn($lockWrapper);

        $wrapperProcessor = new WrapperProcessor(
            wrapperFactoriesMap: [
                DoctrineORMTransactionalWrapper::class => $transactionWrapperFactory,
                LockWrapper::class => $lockWrapperFactory,
            ]
        );

        $handler = new WrappedLockTransactionCommandHandlerStub();

        $this->assertSame($lockWrapper, $wrapperProcessor->wrap($handler));
    }
}
