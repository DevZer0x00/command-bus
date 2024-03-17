<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Transaction\Doctrine\ORM;

use DevZer0x00\CommandBus\Attribute\DoctrineORMTransactionalWrapper;
use DevZer0x00\CommandBus\CommandHandlerInterface;
use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperFactoryInterface;
use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use ReflectionAttribute;

readonly class ORMTransactionHandlerWrapperFactory implements CommandHandlerWrapperFactoryInterface
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
    ) {
    }

    public function factory(
        ReflectionAttribute $attribute,
        CommandHandlerWrapperInterface $wrappedHandler,
        CommandHandlerInterface $originalHandler
    ): CommandHandlerWrapperInterface {
        /** @var DoctrineORMTransactionalWrapper $transactionAttribute */
        $transactionAttribute = $attribute->newInstance();

        $connectionName = $transactionAttribute->connection;

        /** @var ObjectManager $manager */
        $manager = $this->managerRegistry->getManager($connectionName);

        return new ORMTransactionHandlerWrapper(
            wrappedHandler: $wrappedHandler,
            objectManager: $manager,
        );
    }

    public static function getDefaultAttributeName(): string
    {
        return DoctrineORMTransactionalWrapper::class;
    }
}
