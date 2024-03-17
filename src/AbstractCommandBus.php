<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus;

use DevZer0x00\CommandBus\Event\PostHandleEvent;
use DevZer0x00\CommandBus\Event\PreHandleEvent;
use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperInterface;
use DevZer0x00\CommandBus\Wrapper\WrapperProcessorInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

abstract class AbstractCommandBus implements CommandBusInterface
{
    public function __construct(
        private readonly WrapperProcessorInterface $wrapperProcessor,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function handle(object $command): mixed
    {
        $this->eventDispatcher->dispatch(new PreHandleEvent($command));

        $result = $this->wrapHandler($this->getHandler($command))->handle($command);

        $this->eventDispatcher->dispatch(new PostHandleEvent($command));

        return $result;
    }

    protected function wrapHandler(CommandHandlerInterface $handler): CommandHandlerWrapperInterface
    {
        return $this->wrapperProcessor->wrap($handler);
    }

    abstract protected function getHandler(object $command): CommandHandlerInterface;
}
