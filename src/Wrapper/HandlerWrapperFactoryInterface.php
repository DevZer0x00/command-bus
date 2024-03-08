<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\HandlerInterface;
use ReflectionAttribute;

interface HandlerWrapperFactoryInterface
{
    public function factory(
        ReflectionAttribute $attribute,
        HandlerWrapperInterface $wrappedHandler,
        HandlerInterface $originalHandler
    ): HandlerWrapperInterface;
}
