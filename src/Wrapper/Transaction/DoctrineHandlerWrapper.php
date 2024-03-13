<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Transaction;

use DevZer0x00\CommandBus\Wrapper\HandlerWrapperInterface;

class DoctrineHandlerWrapper implements HandlerWrapperInterface
{
    public function __construct(
        private HandlerWrapperInterface $handler,
    ) {
    }

    public function handle($commandObject): mixed
    {
        return $this->handler;
    }
}
