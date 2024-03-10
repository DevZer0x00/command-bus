<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Lock;

use DevZer0x00\CommandBus\Attribute\LockWrapper;
use DevZer0x00\CommandBus\HandlerInterface;
use DevZer0x00\CommandBus\Wrapper\HandlerWrapperFactoryInterface;
use DevZer0x00\CommandBus\Wrapper\HandlerWrapperInterface;
use ReflectionAttribute;

class LockHandlerWrapperFactory implements HandlerWrapperFactoryInterface
{
    public function factory(
        ReflectionAttribute $attribute,
        HandlerWrapperInterface $wrappedHandler,
        HandlerInterface $originalHandler
    ): HandlerWrapperInterface {
        /** @var LockWrapper $lockAttribute */
        $lockAttribute = $attribute->newInstance();

        $lockKey = $this->getLockKey($lockAttribute, $originalHandler);
    }

    public function getLockKey(LockWrapper $lockWrapper, HandlerInterface $originalHandler): string
    {
        
    }
}
