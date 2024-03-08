<?php

declare(strict_types=1);

namespace Tests\Unit\Stubs;

use DevZer0x00\CommandBus\Attribute\TransactionalWrapper;
use DevZer0x00\CommandBus\HandlerInterface;

#[TransactionalWrapper]
class WrappedTransactionAttributeHandlerStub implements HandlerInterface
{
}
