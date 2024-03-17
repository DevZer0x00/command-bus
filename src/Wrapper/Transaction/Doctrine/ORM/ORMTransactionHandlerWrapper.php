<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Transaction\Doctrine\ORM;

use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperInterface;
use Doctrine\Persistence\ObjectManager;

readonly class ORMTransactionHandlerWrapper implements CommandHandlerWrapperInterface
{
    public function __construct(
        private CommandHandlerWrapperInterface $wrappedHandler,
        private ObjectManager $objectManager,
    ) {
    }

    public function handle(object $command): mixed
    {
        $connection = $this->objectManager->getConnection();

        if (!$connection->isTransactionActive()) {
            $result = $this->objectManager->wrapInTransaction(fn() => $this->wrappedHandler->handle($command));
            $this->objectManager->clear();

            return $result;
        }

        return $this->wrappedHandler->handle($command);
    }
}
