<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Lock;

use DevZer0x00\CommandBus\CommandInterface;

interface LockKeyBuilderInterface
{
    public function build(CommandInterface $command): string;
}
