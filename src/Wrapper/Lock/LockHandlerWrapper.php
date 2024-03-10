<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Lock;

use DevZer0x00\CommandBus\Wrapper\HandlerWrapperInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;

readonly class LockHandlerWrapper implements HandlerWrapperInterface
{
    private LockInterface $lock;

    public function __construct(
        private LockFactory $lockFactory,
        private LockKeyBuilderInterface $keyBuilder,
        private int $ttl,
        private HandlerWrapperInterface $handler,
    ) {
    }

    public function handle($commandObject): mixed
    {
        $this->lock = $this->lockFactory->createLock(
            $this->keyBuilder->build($commandObject),
            $this->ttl
        );
        $this->lock->acquire(true);

        $result = $this->handler->handle($commandObject);

        $this->lock->release();

        return $result;
    }
}
