<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Transaction;

use DevZer0x00\CommandBus\Attribute\DoctrineTransactionalWrapper;
use DevZer0x00\CommandBus\HandlerInterface;
use DevZer0x00\CommandBus\Wrapper\HandlerWrapperFactoryInterface;
use DevZer0x00\CommandBus\Wrapper\HandlerWrapperInterface;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\Persistence\ConnectionRegistry;
use ReflectionAttribute;

class DoctrineTransactionHandlerWrapperFactory implements HandlerWrapperFactoryInterface
{
    public function __construct(
        private readonly ConnectionRegistry $connectionRegistry
    ) {
    }

    public function factory(
        ReflectionAttribute $attribute,
        HandlerWrapperInterface $wrappedHandler,
        HandlerInterface $originalHandler
    ): HandlerWrapperInterface {
        /** @var DoctrineTransactionalWrapper $transactionAttribute */
        $transactionAttribute = $attribute->newInstance();

        $connectionName = $transactionAttribute->connection;

        /** @var Connection $connection */
        $connection = $this->connectionRegistry->getConnection($connectionName);
    }

    public static function getDefaultAttributeName(): string
    {
        return DoctrineTransactionalWrapper::class;
    }
}