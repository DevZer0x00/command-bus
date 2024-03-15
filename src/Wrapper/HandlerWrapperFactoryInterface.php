<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\HandlerInterface;
use ReflectionAttribute;

interface HandlerWrapperFactoryInterface
{
    public function factory(
        ReflectionAttribute $attribute,
        HandlerInterface $wrappedHandler,
        HandlerInterface $originalHandler
    ): HandlerInterface;

    public static function getDefaultAttributeName(): string;
}
