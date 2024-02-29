<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Bus;

interface BusInterface
{
    public function handle(mixed $command): mixed;
}
