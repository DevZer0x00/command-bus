<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\CommandHandlerInterface;

interface CommandHandlerWrapperInterface extends CommandHandlerInterface
{
    public function handle(object $command): mixed;
}
