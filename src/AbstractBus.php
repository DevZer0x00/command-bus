<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus;

use DevZer0x00\CommandBus\Wrapper\HandlerWrapperInterface;
use DevZer0x00\CommandBus\Wrapper\WrapperProcessorInterface;

abstract class AbstractBus implements BusInterface
{
    protected int $nestingLevel = 0;

    public function __construct(
        private readonly WrapperProcessorInterface $wrapperProcessor
    ) {
    }

    public function handle(object $command): mixed
    {
        ++$this->nestingLevel;

        $handler = $this->getHandler($command);
        $result = $this->wrapHandler($handler)->handle($command);

        --$this->nestingLevel;

        return $result;
    }

    protected function wrapHandler(HandlerInterface $handler): HandlerWrapperInterface
    {
        return $this->wrapperProcessor->wrap($handler);
    }

    abstract protected function getHandler(mixed $command): HandlerInterface;
}
