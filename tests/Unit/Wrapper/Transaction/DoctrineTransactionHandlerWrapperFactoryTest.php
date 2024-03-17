<?php

declare(strict_types=1);

namespace Tests\Unit\Wrapper\Transaction;

use DevZer0x00\CommandBus\Attribute\DoctrineTransactionalWrapper;
use DevZer0x00\CommandBus\CommandHandlerInterface;
use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperInterface;
use DevZer0x00\CommandBus\Wrapper\Transaction\DoctrineTransactionHandlerWrapper;
use DevZer0x00\CommandBus\Wrapper\Transaction\DoctrineTransactionHandlerWrapperFactory;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ConnectionRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionAttribute;

class DoctrineTransactionHandlerWrapperFactoryTest extends TestCase
{
    private DoctrineTransactionHandlerWrapperFactory $factory;
    private ConnectionRegistry|MockObject $registry;

    protected function setUp(): void
    {
        $this->registry = $this->createMock(ConnectionRegistry::class);

        $this->factory = new DoctrineTransactionHandlerWrapperFactory(
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
            ->willReturn(new DoctrineTransactionalWrapper());

        $handler = $this->factory->factory(
            attribute: $refl,
            wrappedHandler: $this->createMock(CommandHandlerWrapperInterface::class),
            originalHandler: $this->createMock(CommandHandlerInterface::class)
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
