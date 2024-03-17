<?php

declare(strict_types=1);

namespace Tests\Unit\Wrapper\Lock;

use DevZer0x00\CommandBus\Attribute\LockWrapper;
use DevZer0x00\CommandBus\Wrapper\Lock\LockCommandHandlerWrapperFactory;
use PHPUnit\Framework\TestCase;

class LockHandlerWrapperFactoryTest extends TestCase
{
    public function testGetDefaultAttributeName()
    {
        $this->assertEquals(LockWrapper::class, LockCommandHandlerWrapperFactory::getDefaultAttributeName());
    }
}
