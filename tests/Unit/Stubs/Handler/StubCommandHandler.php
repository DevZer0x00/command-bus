<?php

declare(strict_types=1);

namespace Tests\Unit\Stubs\Handler;

use DevZer0x00\CommandBus\CommandHandlerInterface;

class StubCommandHandler implements CommandHandlerInterface
{
    public function handle(object $command): mixed
    {
        return null;
    }
}
