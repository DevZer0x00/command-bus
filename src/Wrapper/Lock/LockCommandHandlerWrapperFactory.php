<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Lock;

use DevZer0x00\CommandBus\Attribute\LockWrapper;
use DevZer0x00\CommandBus\CommandHandlerInterface;
use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperFactoryInterface;
use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperInterface;
use ReflectionAttribute;
use Symfony\Component\Lock\LockFactory;

readonly class LockCommandHandlerWrapperFactory implements CommandHandlerWrapperFactoryInterface
{
    public function __construct(
        private LockFactory $lockFactory,
    ) {
    }

    public function factory(
        ReflectionAttribute $attribute,
        CommandHandlerWrapperInterface $wrappedHandler,
        CommandHandlerInterface $originalHandler
    ): CommandHandlerWrapperInterface {
        /** @var LockWrapper $lockAttribute */
        $lockAttribute = $attribute->newInstance();

        return new LockCommandHandlerWrapper(
            lockFactory: $this->lockFactory,
            keyBuilder: new LockKeyBuilder($lockAttribute, $originalHandler::class),
            ttl: $lockAttribute->ttl,
            wrappedHandler: $wrappedHandler
        );
    }

    public static function getDefaultAttributeName(): string
    {
        return LockWrapper::class;
    }
}
