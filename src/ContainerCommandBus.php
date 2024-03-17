<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus;

use DevZer0x00\CommandBus\Wrapper\WrapperProcessorInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

use function sprintf;

class ContainerCommandBus extends AbstractCommandBus
{
    /**
     * @param array<class-string, string> $handlerMap
     */
    public function __construct(
        private readonly ContainerInterface $container,
        WrapperProcessorInterface $wrapperProcessor,
        EventDispatcherInterface $eventDispatcher,
        private readonly array $handlerMap,
    ) {
        parent::__construct(
            wrapperProcessor: $wrapperProcessor,
            eventDispatcher: $eventDispatcher
        );
    }

    protected function getHandler(object $command): CommandHandlerInterface
    {
        /** @phpstan-ignore-next-line */
        return $this->container->get(
            $this->getHandlerId($command)
        );
    }

    private function getHandlerId(object $command): string
    {
        if (isset($this->handlerMap[$command::class])) {
            return $this->handlerMap[$command::class];
        }

        throw new InvalidArgumentException(sprintf('Command Handler for command %s not found', $command::class));
    }
}
