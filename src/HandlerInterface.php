<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus;

interface HandlerInterface
{
    public function handle(CommandInterface $command): mixed;
}
