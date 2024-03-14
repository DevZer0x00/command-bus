<?php

declare(strict_types=1);

namespace Tests\Unit\Wrapper\Transaction;

use DevZer0x00\CommandBus\Attribute\DoctrineTransactionalWrapper;
use DevZer0x00\CommandBus\HandlerInterface;
use DevZer0x00\CommandBus\Wrapper\HandlerWrapperInterface;
use DevZer0x00\CommandBus\Wrapper\Transaction\DoctrineTransactionHandlerWrapper;
use DevZer0x00\CommandBus\Wrapper\Transaction\DoctrineTransactionHandlerWrapperFactory;
use DevZer0x00\CommandBus\Wrapper\Transaction\DoctrineTransactionStateCheckerInterface;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\Persistence\ConnectionRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionAttribute;

class DoctrineTransactionHandlerWrapperFactoryTest extends TestCase
{
    private DoctrineTransactionHandlerWrapperFactory|MockObject $factory;
    private ConnectionRegistry|MockObject $registry;
    private DoctrineTransactionStateCheckerInterface|MockObject $transactionStateChecker;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ConnectionRegistry::class);
        $this->transactionStateChecker = $this->createMock(DoctrineTransactionStateCheckerInterface::class);

        $this->factory = new DoctrineTransactionHandlerWrapperFactory(
            connectionRegistry: $this->registry,
            transactionStateChecker: $this->transactionStateChecker
        );
    }

    public function testConnectionName()
    {
        $this->registry
            ->expects($this->once())
            ->method('getConnection')
            ->with('default')
            ->willReturn($this->createMock(Connection::class));

        $refl = $this->createMock(ReflectionAttribute::class);
        $refl->expects($this->once())
            ->method('newInstance')
            ->willReturn(new DoctrineTransactionalWrapper());

        $handler = $this->factory->factory(
            attribute: $refl,
            wrappedHandler: $this->createMock(HandlerWrapperInterface::class),
            originalHandler: $this->createMock(HandlerInterface::class)
        );

        $this->assertInstanceOf(DoctrineTransactionHandlerWrapper::class, $handler);
    }

    public function testGetDefaultAttributeName()
    {
        $this->assertEquals(
            DoctrineTransactionalWrapper::class,
            DoctrineTransactionHandlerWrapperFactory::getDefaultAttributeName()
        );
    }
}
