<?php

declare(strict_types=1);

namespace Tests\Unit\Stubs\Handler;

use DevZer0x00\CommandBus\Attribute\DoctrineTransactionalWrapper;
use DevZer0x00\CommandBus\CommandHandlerInterface;

#[DoctrineTransactionalWrapper]
class WrappedTransactionAttributeCommandHandlerStub implements CommandHandlerInterface
{
    public function handle(object $command): mixed
    {
        return null;
    }
}
