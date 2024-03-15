<?php

declare(strict_types=1);

namespace Tests\Functional\Stubs\Wrapper;

use DevZer0x00\CommandBus\HandlerInterface;
use DevZer0x00\CommandBus\Wrapper\HandlerWrapperFactoryInterface;
use DevZer0x00\CommandBus\Wrapper\HandlerWrapperInterface;
use ReflectionAttribute;

class TestHandlerWrapperFactory implements HandlerWrapperFactoryInterface
{
    public function factory(
        ReflectionAttribute $attribute,
        HandlerInterface $wrappedHandler,
        HandlerInterface $originalHandler
    ): HandlerWrapperInterface {
        // TODO: Implement factory() method.
    }

    public static function getDefaultAttributeName(): string
    {
        return 'test';
    }
}
