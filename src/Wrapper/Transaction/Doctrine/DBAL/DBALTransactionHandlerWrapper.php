<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Transaction\Doctrine\DBAL;

use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperInterface;
use Doctrine\DBAL\Connection;
use Throwable;

class DBALTransactionHandlerWrapper implements CommandHandlerWrapperInterface
{
    private bool $starter = false;

    public function __construct(
        private CommandHandlerWrapperInterface $wrappedHandler,
        private Connection $connection,
    ) {
    }

    public function handle(object $command): mixed
    {
        if (!$this->connection->isTransactionActive()) {
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
