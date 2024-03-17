<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Event;

use Symfony\Contracts\EventDispatcher\Event;

class PostHandleEvent extends Event
{
    public function __construct(public readonly object $command)
    {
    }
}
