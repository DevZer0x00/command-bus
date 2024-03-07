<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\CommandHandlerInterface;
use Psr\Container\ContainerInterface;
use ReflectionClass;

readonly class WrapperProcessor implements WrapperProcessorInterface
{
    public function __construct(
        private ContainerInterface $container,
        private array $wrapperFactoriesMap,
    ) {
    }

    public function wrap(CommandHandlerInterface $handler): HandlerWrapperInterface
    {
        $originalHandler = $handler;
        $handler = new NopHandlerWrapper($handler);

        $reflClass = new ReflectionClass($originalHandler);
        $attributes = $reflClass->getAttributes();

        foreach ($attributes as $attribute) {
            if (($factoryClass = $this->wrapperFactoriesMap[$attribute->getName()] ?? null) === null) {
                continue;
            }

            /** @var HandlerWrapperFactoryInterface $factory */
            $factory = $this->container->get($factoryClass);
            $handler = $factory->factory(
                attribute: $attribute,
                wrappedHandler: $handler,
                originalHandler: $originalHandler
            );
        }

        return $handler;
    }
}
