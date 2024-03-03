<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

use function sprintf;

class ContainerCommandBus extends AbstractCommandBus
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly array $handlerMap,
    ) {
    }

    protected function getHandler(mixed $command): CommandHandlerInterface
    {
        return $this->container->get(
            $this->getHandlerId($command)
        );
    }

    private function getHandlerId(mixed $command): string
    {
        if (isset($this->handlerMap[$command::class])) {
            return $this->handlerMap[$command::class];
        }

        throw new InvalidArgumentException(sprintf('Command Handler for command %s not found', $command::class));
    }
}
