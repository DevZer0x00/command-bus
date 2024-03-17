<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper;

use DevZer0x00\CommandBus\CommandHandlerInterface;
use ReflectionAttribute;
use ReflectionClass;

use function array_combine;
use function array_map;
use function iterator_to_array;

readonly class WrapperProcessor implements WrapperProcessorInterface
{
    /**
     * @var array<class-string, CommandHandlerWrapperFactoryInterface>
     */
    private array $wrapperFactoriesMap;

    /**
     * @param iterable<class-string, CommandHandlerWrapperFactoryInterface> $wrapperFactoriesMap
     */
    public function __construct(
        iterable $wrapperFactoriesMap,
    ) {
        $this->wrapperFactoriesMap = iterator_to_array($wrapperFactoriesMap);
    }

    public function wrap(CommandHandlerInterface $handler): CommandHandlerInterface
    {
        $originalHandler = $handler;
        $handler = new NopCommandHandlerWrapper($handler);

        $reflectionClass = new ReflectionClass($originalHandler);
        $attributes = $reflectionClass->getAttributes();
        $attributes = array_combine(
            array_map(
                fn(ReflectionAttribute $attribute) => $attribute->getName(),
                $attributes
            ),
            $attributes
        );

        foreach ($this->wrapperFactoriesMap as $attributeClass => $factory) {
            if (!isset($attributes[$attributeClass])) {
                continue;
            }

            $handler = $factory->factory(
                attribute: $attributes[$attributeClass],
                wrappedHandler: $handler,
                originalHandler: $originalHandler
            );
        }

        return $handler;
    }
}
