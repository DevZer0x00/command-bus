<?php

declare(strict_types=1);

namespace Tests\Unit\Stubs\Handler;

use DevZer0x00\CommandBus\Attribute\DoctrineTransactionalWrapper;
use DevZer0x00\CommandBus\Attribute\LockWrapper;
use DevZer0x00\CommandBus\CommandHandlerInterface;

#[LockWrapper]
#[DoctrineTransactionalWrapper]
class WrappedLockTransactionCommandHandlerStub implements CommandHandlerInterface
{
    public function handle(object $command): mixed
    {
        return null;
    }
}
