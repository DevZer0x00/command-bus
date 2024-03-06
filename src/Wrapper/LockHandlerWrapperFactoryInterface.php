<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\Attribute\LockWrapper;
use DevZer0x00\CommandBus\CommandHandlerInterface;

interface LockHandlerWrapperFactoryInterface
{
    public function factory(
        LockWrapper $attribute,
        CommandHandlerInterface $wrappedHandler,
        CommandHandlerInterface $originalHandler
    ): HandlerWrapperInterface;
}
