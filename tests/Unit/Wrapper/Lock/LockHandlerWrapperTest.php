<?php

declare(strict_types=1);

namespace Tests\Unit\Wrapper\Lock;

use DevZer0x00\CommandBus\Wrapper\HandlerWrapperInterface;
use DevZer0x00\CommandBus\Wrapper\Lock\LockHandlerWrapper;
use DevZer0x00\CommandBus\Wrapper\Lock\LockKeyBuilderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\SharedLockInterface;

class LockHandlerWrapperTest extends TestCase
{
    public function testLock()
    {
        $lock = $this->createMock(SharedLockInterface::class);
        $lock->expects($this->once())->method('acquire')->with(true)->willReturn(true);

        $lockFactory = $this->createMock(LockFactory::class);
        $lockFactory->expects($this->once())->method('createLock')->with('test', 10, true)->willReturn($lock);

        $lockKeyBuilder = $this->createMock(LockKeyBuilderInterface::class);
        $lockKeyBuilder->expects($this->once())->method('build')->willReturn('test');

        $handler = $this->createMock(HandlerWrapperInterface::class);
        $handler->expects($this->once())->method('handle')->willReturn(1);

        $lockWrapper = new LockHandlerWrapper(
            lockFactory: $lockFactory,
            keyBuilder: $lockKeyBuilder,
            ttl: 10,
            handler: $handler
        );

        $result = $lockWrapper->handle(
            new class {

            }
        );

        $this->assertEquals(1, $result);
    }
}
