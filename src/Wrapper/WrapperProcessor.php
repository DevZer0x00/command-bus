<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\CommandHandlerInterface;
use ReflectionClass;

class WrapperProcessor implements WrapperProcessorInterface
{
    public function __construct(
        array $wrapperFactoriesMap,
    ) {
    }

    public function wrap(CommandHandlerInterface $handler): HandlerWrapperInterface
    {
        $originalHandler = $wrappedHandler = $handler;

        $reflClass = new ReflectionClass($handler);
        $attributes = $reflClass->getAttributes();


    }
}
