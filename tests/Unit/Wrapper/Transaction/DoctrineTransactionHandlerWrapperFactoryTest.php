<?php

declare(strict_types=1);

namespace Tests\Unit\Wrapper\Transaction;

use DevZer0x00\CommandBus\Attribute\DoctrineTransactionalWrapper;
use DevZer0x00\CommandBus\CommandHandlerInterface;
use DevZer0x00\CommandBus\Wrapper\Transaction\DoctrineTransactionCommandHandlerWrapper;
use DevZer0x00\CommandBus\Wrapper\Transaction\DoctrineTransactionCommandHandlerWrapperFactory;
use DevZer0x00\CommandBus\Wrapper\Transaction\DoctrineTransactionStateCheckerInterface;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\Persistence\ConnectionRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionAttribute;

class DoctrineTransactionHandlerWrapperFactoryTest extends TestCase
{
    private DoctrineTransactionCommandHandlerWrapperFactory $factory;
    private ConnectionRegistry|MockObject $registry;
    private DoctrineTransactionStateCheckerInterface|MockObject $transactionStateChecker;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ConnectionRegistry::class);
        $this->transactionStateChecker = $this->createMock(DoctrineTransactionStateCheckerInterface::class);

        $this->factory = new DoctrineTransactionCommandHandlerWrapperFactory(
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
            wrappedHandler: $this->createMock(CommandHandlerInterface::class),
            originalHandler: $this->createMock(CommandHandlerInterface::class)
        );

        $this->assertInstanceOf(DoctrineTransactionCommandHandlerWrapper::class, $handler);
    }

    public function testGetDefaultAttributeName()
    {
        $this->assertEquals(
            DoctrineTransactionalWrapper::class,
            DoctrineTransactionCommandHandlerWrapperFactory::getDefaultAttributeName()
        );
    }
}
