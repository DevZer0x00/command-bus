<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\CommandHandlerInterface;

interface HandlerWrapperInterface extends CommandHandlerInterface
{
    public function preHandle(int $nestingLevel): void;

    public function handle($commandObject): mixed;

    public function postHandle(int $nestingLevel): void;
}
