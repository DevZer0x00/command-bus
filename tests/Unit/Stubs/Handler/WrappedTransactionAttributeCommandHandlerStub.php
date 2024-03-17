<?php

declare(strict_types=1);

namespace Tests\Unit\Stubs\Handler;

use DevZer0x00\CommandBus\Attribute\DoctrineORMTransactionalWrapper;
use DevZer0x00\CommandBus\CommandHandlerInterface;

#[DoctrineORMTransactionalWrapper]
class WrappedTransactionAttributeCommandHandlerStub implements CommandHandlerInterface
{
    public function handle(object $command): mixed
    {
        return null;
    }
}
