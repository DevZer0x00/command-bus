<?php

declare(strict_types=1);

namespace Tests\Functional\Stubs\Command\Handler;

use DevZer0x00\CommandBus\CommandInterface;
use DevZer0x00\CommandBus\HandlerInterface;
use Tests\Functional\Stubs\Command\TestCommand;

class TestHandler implements HandlerInterface
{
    public function handle(CommandInterface|TestCommand $command): string
    {
        return $command->name;
    }
}
