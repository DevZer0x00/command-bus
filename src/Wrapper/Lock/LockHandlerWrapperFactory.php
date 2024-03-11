<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Lock;

use DevZer0x00\CommandBus\Attribute\LockWrapper;
use DevZer0x00\CommandBus\HandlerInterface;
use DevZer0x00\CommandBus\Wrapper\HandlerWrapperFactoryInterface;
use DevZer0x00\CommandBus\Wrapper\HandlerWrapperInterface;
use ReflectionAttribute;
use Symfony\Component\Lock\LockFactory;

readonly class LockHandlerWrapperFactory implements HandlerWrapperFactoryInterface
{
    public function __construct(
        private LockFactory $lockFactory,
    ) {
    }

    public function factory(
        ReflectionAttribute $attribute,
        HandlerWrapperInterface $wrappedHandler,
        HandlerInterface $originalHandler
    ): HandlerWrapperInterface {
        /** @var LockWrapper $lockAttribute */
        $lockAttribute = $attribute->newInstance();

        return new LockHandlerWrapper(
            lockFactory: $this->lockFactory,
            keyBuilder: new LockKeyBuilder($lockAttribute, $originalHandler::class),
            ttl: $lockAttribute->ttl,
            handler: $wrappedHandler
        );
    }

    public static function getDefaultAttributeName(): string
    {
        return LockWrapper::class;
    }
}
