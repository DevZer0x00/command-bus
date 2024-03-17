<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\CommandHandlerInterface;

interface WrapperProcessorInterface
{
    public function wrap(CommandHandlerInterface $handler): CommandHandlerWrapperInterface;
}
