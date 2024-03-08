<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\CommandHandlerInterface;

readonly class NopHandlerWrapper implements HandlerWrapperInterface
{
    public function __construct(private CommandHandlerInterface $handler)
    {
    }

    public function preHandle(int $nestingLevel): void
    {
    }

    public function handle($commandObject): mixed
    {
        return $this->handler->handle($commandObject);
    }

    public function postHandle(int $nestingLevel): void
    {
    }
}
