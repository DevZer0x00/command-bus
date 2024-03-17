<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Transaction\Doctrine\DBAL;

use DevZer0x00\CommandBus\Attribute\DoctrineDBALTransactionalWrapper;
use DevZer0x00\CommandBus\CommandHandlerInterface;
use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperFactoryInterface;
use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperInterface;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ConnectionRegistry;
use ReflectionAttribute;

readonly class DBALTransactionHandlerWrapperFactory implements CommandHandlerWrapperFactoryInterface
{
    public function __construct(
        private ConnectionRegistry $connectionRegistry,
    ) {
    }

    public function factory(
        ReflectionAttribute $attribute,
        CommandHandlerWrapperInterface $wrappedHandler,
        CommandHandlerInterface $originalHandler
    ): CommandHandlerWrapperInterface {
        /** @var DoctrineDBALTransactionalWrapper $transactionAttribute */
        $transactionAttribute = $attribute->newInstance();

        $connectionName = $transactionAttribute->connection;

        /** @var Connection $connection */
        $connection = $this->connectionRegistry->getConnection($connectionName);

        return new DBALTransactionHandlerWrapper(
            wrappedHandler: $wrappedHandler,
            connection: $connection,
        );
    }

    public static function getDefaultAttributeName(): string
    {
        return DoctrineDBALTransactionalWrapper::class;
    }
}
