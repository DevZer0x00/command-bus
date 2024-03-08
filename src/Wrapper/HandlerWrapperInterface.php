<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\HandlerInterface;

interface HandlerWrapperInterface extends HandlerInterface
{
    public function preHandle(int $nestingLevel): void;

    public function handle($commandObject): mixed;

    public function postHandle(int $nestingLevel): void;
}
