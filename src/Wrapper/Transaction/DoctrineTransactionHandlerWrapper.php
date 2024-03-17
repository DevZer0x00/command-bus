<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Transaction;

use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperInterface;
use Doctrine\DBAL\Driver\Connection;
use Throwable;

class DoctrineTransactionHandlerWrapper implements CommandHandlerWrapperInterface
{
    private bool $starter = false;

    public function __construct(
        private readonly CommandHandlerWrapperInterface $wrappedHandler,
        private readonly Connection $connection,
        private readonly DoctrineTransactionStateCheckerInterface $transactionStateChecker,
    ) {
    }

    public function handle(object $command): mixed
    {
        if (!$this->transactionStateChecker->inTransaction($this->connection)) {
            $this->connection->beginTransaction();
            $this->starter = true;
        }

        try {
            $result = $this->wrappedHandler->handle($command);

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
