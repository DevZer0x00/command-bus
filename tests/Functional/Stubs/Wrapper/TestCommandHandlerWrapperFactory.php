<?php

declare(strict_types=1);

namespace Tests\Functional\Stubs\Wrapper;

use DevZer0x00\CommandBus\CommandHandlerInterface;
use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperFactoryInterface;
use ReflectionAttribute;

class TestCommandHandlerWrapperFactory implements CommandHandlerWrapperFactoryInterface
{
    public function factory(
        ReflectionAttribute $attribute,
        CommandHandlerInterface $wrappedHandler,
        CommandHandlerInterface $originalHandler
    ): CommandHandlerInterface {
        // TODO: Implement factory() method.
    }

    public static function getDefaultAttributeName(): string
    {
        return 'test';
    }
}
