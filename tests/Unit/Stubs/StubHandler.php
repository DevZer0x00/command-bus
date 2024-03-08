<?php

declare(strict_types=1);

namespace Tests\Unit\Stubs;

use DevZer0x00\CommandBus\HandlerInterface;

class StubHandler implements HandlerInterface
{
    public function handle(object $command): mixed
    {
        return null;
    }
}
