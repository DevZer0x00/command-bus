<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Transaction;

use DevZer0x00\CommandBus\CommandInterface;
use DevZer0x00\CommandBus\HandlerInterface;
use Doctrine\DBAL\Driver\Connection;
use Throwable;

class DoctrineTransactionHandlerWrapper implements HandlerInterface
{
    private bool $starter = false;

    public function __construct(
        private readonly HandlerInterface $handler,
        private readonly Connection $connection,
        private readonly DoctrineTransactionStateCheckerInterface $transactionStateChecker,
    ) {
    }

    public function handle(CommandInterface $command): mixed
    {
        if (!$this->transactionStateChecker->inTransaction($this->connection)) {
            $this->connection->beginTransaction();
            $this->starter = true;
        }

        try {
            $result = $this->handler->handle($command);

            if ($this->starter) {
                $this->connection->commit();
            }

            return $result;
        } catch (Throwable $e) {
            if ($this->starter) {
                $this->connection->rollBack();
            }

            throw $e;
        }
    }
}
