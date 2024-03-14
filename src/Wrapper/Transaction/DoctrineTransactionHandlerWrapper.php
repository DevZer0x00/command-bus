<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Transaction;

use DevZer0x00\CommandBus\Wrapper\HandlerWrapperInterface;
use Doctrine\DBAL\Driver\Connection;
use Throwable;

class DoctrineTransactionHandlerWrapper implements HandlerWrapperInterface
{
    private bool $starter = false;

    public function __construct(
        private HandlerWrapperInterface $handler,
        private Connection $connection,
        private DoctrineTransactionStateCheckerInterface $transactionStateChecker,
    ) {
    }

    public function handle($commandObject): mixed
    {
        if (!$this->transactionStateChecker->inTransaction($this->connection)) {
            $this->connection->beginTransaction();
            $this->starter = true;
        }

        try {
            $result = $this->handler->handle($commandObject);

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
