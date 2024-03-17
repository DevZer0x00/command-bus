<?php

declare(strict_types=1);

namespace Tests\Unit\Wrapper\Transaction\Doctrine\DBAL;

use DevZer0x00\CommandBus\Attribute\DoctrineDBALTransactionalWrapper;
use DevZer0x00\CommandBus\Attribute\DoctrineORMTransactionalWrapper;
use DevZer0x00\CommandBus\CommandHandlerInterface;
use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperInterface;
use DevZer0x00\CommandBus\Wrapper\Transaction\Doctrine\DBAL\DBALTransactionHandlerWrapper;
use DevZer0x00\CommandBus\Wrapper\Transaction\Doctrine\DBAL\DBALTransactionHandlerWrapperFactory;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ConnectionRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionAttribute;

class DBALTransactionHandlerWrapperFactoryTest extends TestCase
{
    private DBALTransactionHandlerWrapperFactory $factory;
    private ConnectionRegistry|MockObject $registry;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ConnectionRegistry::class);

        $this->factory = new DBALTransactionHandlerWrapperFactory(
            connectionRegistry: $this->registry,
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
            ->willReturn(new DoctrineORMTransactionalWrapper());

        $handler = $this->factory->factory(
            attribute: $refl,
            wrappedHandler: $this->createMock(CommandHandlerWrapperInterface::class),
            originalHandler: $this->createMock(CommandHandlerInterface::class)
        );

        $this->assertInstanceOf(DBALTransactionHandlerWrapper::class, $handler);
    }

    public function testGetDefaultAttributeName()
    {
        $this->assertEquals(
            DoctrineDBALTransactionalWrapper::class,
            DBALTransactionHandlerWrapperFactory::getDefaultAttributeName()
        );
    }
}
