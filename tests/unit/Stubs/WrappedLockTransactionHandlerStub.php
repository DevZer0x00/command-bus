<?php

declare(strict_types=1);

namespace Tests\unit\Stubs;

use DevZer0x00\CommandBus\Attribute\LockWrapper;
use DevZer0x00\CommandBus\Attribute\TransactionalWrapper;
use DevZer0x00\CommandBus\CommandHandlerInterface;

#[LockWrapper]
#[TransactionalWrapper]
class WrappedLockTransactionHandlerStub implements CommandHandlerInterface
{
}
