<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus;

interface BusInterface
{
    public function handle(object $command): mixed;
}
