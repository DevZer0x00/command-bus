<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\Attribute\TransactionalWrapper;
use DevZer0x00\CommandBus\CommandHandlerInterface;

interface TransactionalHandlerWrapperFactoryInterface
{
    public function factory(
        TransactionalWrapper $attribute,
        CommandHandlerInterface $wrappedHandler,
        CommandHandlerInterface $originalHandler
    ): HandlerWrapperInterface;
}
