<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus;

use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperInterface;
use DevZer0x00\CommandBus\Wrapper\WrapperProcessorInterface;

abstract class AbstractCommandBus implements CommandBusInterface
{
    public function __construct(
        private readonly WrapperProcessorInterface $wrapperProcessor
    ) {
    }

    public function handle(object $command): mixed
    {
        $handler = $this->getHandler($command);
        $result = $this->wrapHandler($handler)->handle($command);

        return $result;
    }

    protected function wrapHandler(CommandHandlerInterface $handler): CommandHandlerWrapperInterface
    {
        return $this->wrapperProcessor->wrap($handler);
    }

    abstract protected function getHandler(object $command): CommandHandlerInterface;
}
