<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class TransactionalWrapped
{
}
