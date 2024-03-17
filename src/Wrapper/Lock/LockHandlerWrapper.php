<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Lock;

use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperInterface;
use Symfony\Component\Lock\LockFactory;

readonly class LockHandlerWrapper implements CommandHandlerWrapperInterface
{
    public function __construct(
        private LockFactory $lockFactory,
        private LockKeyBuilderInterface $keyBuilder,
        private int $ttl,
        private CommandHandlerWrapperInterface $wrappedHandler,
    ) {
    }

    public function handle(object $command): mixed
    {
        $lock = $this->lockFactory->createLock(
            $this->keyBuilder->build($command),
            $this->ttl
        );
        $lock->acquire(true);

        $result = $this->wrappedHandler->handle($command);

        $lock->release();

        return $result;
    }
}
