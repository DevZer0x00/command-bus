<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\CommandHandlerInterface;

readonly class NopCommandHandlerWrapper implements CommandHandlerWrapperInterface
{
    public function __construct(private CommandHandlerInterface $handler)
    {
    }

    public function handle(object $command): mixed
    {
        /** @phpstan-ignore-next-line */
        return $this->handler->handle($command);
    }
}
