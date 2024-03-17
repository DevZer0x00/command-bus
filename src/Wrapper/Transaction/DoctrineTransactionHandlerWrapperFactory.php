<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Transaction;

use DevZer0x00\CommandBus\Attribute\DoctrineTransactionalWrapper;
use DevZer0x00\CommandBus\CommandHandlerInterface;
use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperFactoryInterface;
use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperInterface;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\Persistence\ConnectionRegistry;
use ReflectionAttribute;

readonly class DoctrineTransactionHandlerWrapperFactory implements CommandHandlerWrapperFactoryInterface
{
    public function __construct(
        private ConnectionRegistry $connectionRegistry,
        private DoctrineTransactionStateCheckerInterface $transactionStateChecker,
    ) {
    }

    public function factory(
        ReflectionAttribute $attribute,
        CommandHandlerWrapperInterface $wrappedHandler,
        CommandHandlerInterface $originalHandler
    ): CommandHandlerWrapperInterface {
        /** @var DoctrineTransactionalWrapper $transactionAttribute */
        $transactionAttribute = $attribute->newInstance();

        $connectionName = $transactionAttribute->connection;

        /** @var Connection $connection */
        $connection = $this->connectionRegistry->getConnection($connectionName);

        return new DoctrineTransactionHandlerWrapper(
            wrappedHandler: $wrappedHandler,
            connection: $connection,
            transactionStateChecker: $this->transactionStateChecker
        );
    }

    public static function getDefaultAttributeName(): string
    {
        return DoctrineTransactionalWrapper::class;
    }
}
