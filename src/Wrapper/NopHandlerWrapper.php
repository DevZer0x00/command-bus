<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\HandlerInterface;

readonly class NopHandlerWrapper implements HandlerWrapperInterface
{
    public function __construct(private HandlerInterface $handler)
    {
    }

    public function handle($commandObject): mixed
    {
        return $this->handler->handle($commandObject);
    }
}
