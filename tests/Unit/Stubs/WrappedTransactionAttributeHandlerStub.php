<?php

declare(strict_types=1);

namespace Tests\Unit\Stubs;

use DevZer0x00\CommandBus\Attribute\DoctrineTransactionalWrapper;
use DevZer0x00\CommandBus\HandlerInterface;

#[DoctrineTransactionalWrapper]
class WrappedTransactionAttributeHandlerStub implements HandlerInterface
{
}
