<?php

declare(strict_types=1);

namespace Tests\Functional\Stubs\Wrapper;

use DevZer0x00\CommandBus\CommandHandlerInterface;
use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperFactoryInterface;
use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperInterface;
use ReflectionAttribute;

class TestCommandHandlerWrapperFactory implements CommandHandlerWrapperFactoryInterface
{
    public function factory(
        ReflectionAttribute $attribute,
        CommandHandlerWrapperInterface $wrappedHandler,
        CommandHandlerInterface $originalHandler
    ): CommandHandlerWrapperInterface {
        // TODO: Implement factory() method.
    }

    public static function getDefaultAttributeName(): string
    {
        return 'test';
    }
}
