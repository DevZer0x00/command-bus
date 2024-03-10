<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Lock;

interface LockKeyBuilderInterface
{
    public function build($commandObject): string;
}
