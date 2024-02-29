<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class LockWrapped
{
    public function __construct(
        public array $commandFields = [],
        public ?string $lockKey = null,
        public int $ttl = 300,
    ) {
    }
}
