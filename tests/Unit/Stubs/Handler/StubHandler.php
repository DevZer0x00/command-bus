<?php

declare(strict_types=1);

namespace Tests\Unit\Stubs\Handler;

use DevZer0x00\CommandBus\CommandInterface;
use DevZer0x00\CommandBus\HandlerInterface;

class StubHandler implements HandlerInterface
{
    public function handle(CommandInterface $command): mixed
    {
        return null;
    }
}
