<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\HandlerInterface;
use Psr\Container\ContainerInterface;
use ReflectionAttribute;
use ReflectionClass;

use function array_combine;
use function array_map;

readonly class WrapperProcessor implements WrapperProcessorInterface
{
    public function __construct(
        private ContainerInterface $container,
        private array $wrapperFactoriesMap,
    ) {
    }

    public function wrap(HandlerInterface $handler): HandlerWrapperInterface
    {
        $originalHandler = $handler;
        $handler = new NopHandlerWrapper($handler);

        $reflClass = new ReflectionClass($originalHandler);
        $attributes = $reflClass->getAttributes();
        $attributes = array_combine(
            array_map(
                fn(ReflectionAttribute $attribute) => $attribute->getName(),
                $attributes
            ),
            $attributes
        );

        foreach ($this->wrapperFactoriesMap as $attributeClass => $factoryClass) {
            if (!isset($attributes[$attributeClass])) {
                continue;
            }

            /** @var HandlerWrapperFactoryInterface $factory */
            $factory = $this->container->get($factoryClass);
            $handler = $factory->factory(
                attribute: $attributes[$attributeClass],
                wrappedHandler: $handler,
                originalHandler: $originalHandler
            );
        }

        return $handler;
    }
}
