<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\CommandHandlerInterface;
use ReflectionAttribute;

interface HandlerWrapperFactoryInterface
{
    public function factory(
        ReflectionAttribute $attribute,
        HandlerWrapperInterface $wrappedHandler,
        CommandHandlerInterface $originalHandler
    ): HandlerWrapperInterface;
}
