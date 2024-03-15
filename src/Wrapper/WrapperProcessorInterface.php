<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\HandlerInterface;

interface WrapperProcessorInterface
{
    public function wrap(HandlerInterface $handler): HandlerInterface;
}
