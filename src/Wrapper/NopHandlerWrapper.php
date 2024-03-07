<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\CommandHandlerInterface;

readonly class NopHandlerWrapper implements HandlerWrapperInterface
{
    public function __construct(private CommandHandlerInterface $handler)
    {
    }

    public function preHandle(): void
    {
    }

    public function handle(object $command): mixed
    {
        return $this->handler->handle($command);
    }

    public function postHandle(): void
    {
    }
}
