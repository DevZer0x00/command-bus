<?php

declare(strict_types=1);

namespace Tests\Functional\Stubs\Command\Handler;

use DevZer0x00\CommandBus\CommandHandlerInterface;
use Tests\Functional\Stubs\Command\TestCommand;

class TestHandler implements CommandHandlerInterface
{
    public function handle(TestCommand $command): string
    {
        return $command->name;
    }
}
