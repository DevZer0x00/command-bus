<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus;

abstract class AbstractCommandBus implements CommandBusInterface
{
    protected int $nestingLevel = 0;

    public function handle(object $command): mixed
    {
        ++$this->nestingLevel;

        $handler = $this->getHandler($command);
        $result = $this->wrapHandler($handler)->handle($command);

        --$this->nestingLevel;

        return $result;
    }

    protected function wrapHandler(CommandHandlerInterface $handler): CommandHandlerInterface
    {
        return $handler;
    }

    abstract protected function getHandler(mixed $command): CommandHandlerInterface;
}
