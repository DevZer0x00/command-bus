<?php

declare(strict_types=1);

namespace Tests\Unit\Wrapper\Transaction;

use DevZer0x00\CommandBus\CommandHandlerInterface;
use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperInterface;
use DevZer0x00\CommandBus\Wrapper\Transaction\DoctrineTransactionHandlerWrapper;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;

class DoctrineTransactionHandlerWrapperTest extends TestCase
{
    private DoctrineTransactionHandlerWrapper $handlerWrapper;
    private CommandHandlerInterface|MockObject $handler;
    private Connection|MockObject $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);

        $this->handler = $this->createMock(CommandHandlerWrapperInterface::class);

        $this->handlerWrapper = new DoctrineTransactionHandlerWrapper(
            wrappedHandler: $this->handler,
            connection: $this->connection,
        );
    }

    public function testTransactionNotStarted()
    {
        $this->connection
            ->expects($this->once())
            ->method('isTransactionActive')
            ->willReturn(false);

        $this->connection
            ->expects($this->once())
            ->method('beginTransaction');

        $this->connection
            ->expects($this->once())
            ->method('commit');

        $this->handler
            ->expects($this->once())
            ->method('handle');

        $this->handlerWrapper->handle(new stdClass());
    }

    public function testTransactionIgnoreWhenStarted()
    {
        $this->connection
            ->expects($this->once())
            ->method('isTransactionActive')
            ->willReturn(true);

        $this->connection
            ->expects($this->never())
            ->method('beginTransaction');

        $this->connection
            ->expects($this->never())
            ->method('commit');

        $this->handler
            ->expects($this->once())
            ->method('handle');

        $this->handlerWrapper->handle(new stdClass());
    }

    public function testTransactionRollback()
    {
        $this->expectException(RuntimeException::class);

        $this->connection
            ->expects($this->once())
            ->method('isTransactionActive')
            ->willReturn(false);

        $this->connection
            ->expects($this->once())
            ->method('beginTransaction');

        $this->connection
            ->expects($this->never())
            ->method('commit');

        $this->connection
            ->expects($this->once())
            ->method('rollBack');

        $this->handler
            ->expects($this->once())
            ->method('handle')
            ->willThrowException(new RuntimeException());

        $this->handlerWrapper->handle(new stdClass());
    }
}
