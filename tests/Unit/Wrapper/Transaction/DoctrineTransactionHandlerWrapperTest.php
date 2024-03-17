<?php

declare(strict_types=1);

namespace Tests\Unit\Wrapper\Transaction;

use DevZer0x00\CommandBus\CommandInterface;
use DevZer0x00\CommandBus\CommandHandlerInterface;
use DevZer0x00\CommandBus\Wrapper\Transaction\DoctrineTransactionCommandHandlerWrapper;
use DevZer0x00\CommandBus\Wrapper\Transaction\DoctrineTransactionStateCheckerInterface;
use Doctrine\DBAL\Driver\Connection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;

class DoctrineTransactionHandlerWrapperTest extends TestCase
{
    private DoctrineTransactionCommandHandlerWrapper $handlerWrapper;
    private CommandHandlerInterface|MockObject $handler;
    private Connection|MockObject $connection;
    private DoctrineTransactionStateCheckerInterface|MockObject $transactionStateChecker;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->transactionStateChecker = $this->createMock(DoctrineTransactionStateCheckerInterface::class);

        $this->handler = $this->createMock(CommandHandlerInterface::class);

        $this->handlerWrapper = new DoctrineTransactionCommandHandlerWrapper(
            wrappedHandler: $this->handler,
            connection: $this->connection,
            transactionStateChecker: $this->transactionStateChecker
        );
    }

    public function testTransactionNotStarted()
    {
        $this->transactionStateChecker
            ->expects($this->once())
            ->method('inTransaction')
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

        $this->handlerWrapper->handle(new class implements CommandInterface {
        });
    }

    public function testTransactionIgnoreWhenStarted()
    {
        $this->transactionStateChecker
            ->expects($this->once())
            ->method('inTransaction')
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

        $this->handlerWrapper->handle(new class implements CommandInterface {
        });
    }

    public function testTransactionRollback()
    {
        $this->expectException(RuntimeException::class);

        $this->transactionStateChecker
            ->expects($this->once())
            ->method('inTransaction')
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
