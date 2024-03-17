<?php

declare(strict_types=1);

namespace Tests\Functional\Stubs\Command;

readonly class TestCommand
{
    public function __construct(
        public string $name
    ) {
    }
}
