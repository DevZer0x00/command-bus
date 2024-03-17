<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\CommandHandlerInterface;
use ReflectionAttribute;

interface CommandHandlerWrapperFactoryInterface
{
    public function factory(
        ReflectionAttribute $attribute,
        CommandHandlerWrapperInterface $wrappedHandler,
        CommandHandlerInterface $originalHandler
    ): CommandHandlerWrapperInterface;

    public static function getDefaultAttributeName(): string;
}
