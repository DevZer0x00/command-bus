<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Lock;

use DevZer0x00\CommandBus\CommandInterface;
use DevZer0x00\CommandBus\HandlerInterface;
use Symfony\Component\Lock\LockFactory;

readonly class LockHandlerWrapper implements HandlerInterface
{
    public function __construct(
        private LockFactory $lockFactory,
        private LockKeyBuilderInterface $keyBuilder,
        private int $ttl,
        private HandlerInterface $handler,
    ) {
    }

    public function handle(CommandInterface $command): mixed
    {
        $lock = $this->lockFactory->createLock(
            $this->keyBuilder->build($command),
            $this->ttl
        );
        $lock->acquire(true);

        $result = $this->handler->handle($command);

        $lock->release();

        return $result;
    }
}
