<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\DependencyInjection\Compiler;

use DevZer0x00\CommandBus\CommandBusInterface;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;
use RuntimeException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\TypedReference;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use function array_filter;
use function array_map;
use function sprintf;

class CommandHandlerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('app.command_handler');

        $handlerMap = [];
        $refMaps = [];

        foreach ($taggedServices as $id => $tags) {
            $definition = $container->getDefinition($id);
            /** @var string $class */
            $class = $container->getParameterBag()->resolveValue($definition->getClass());
            /** @phpstan-ignore-next-line */
            $commandClasses = $this->getCommandType($container->getReflectionClass($class));

            if (empty($commandClasses)) {
                throw new RuntimeException(sprintf('Not found command for handler %s', $class));
            }

            foreach ($commandClasses as $commandClass) {
                $handlerMap[$commandClass] = $id;
                $refMaps[$id] = new TypedReference($id, $class);
            }
        }

        $id = EventDispatcherInterface::class;
        $refMaps[$id] = new Reference($id);

        $busDefinition = $container->getDefinition(CommandBusInterface::class);
        $busDefinition->setArgument('$container', ServiceLocatorTagPass::register($container, $refMaps));
        $busDefinition->setArgument('$handlerMap', $handlerMap);
    }

    /**
     * @return array<string>
     */
    private function getCommandType(ReflectionClass $reflectionClass): array
    {
        if (!$reflectionClass->hasMethod('handle')) {
            throw new InvalidArgumentException(
                sprintf('Command Handler %s does not have method handle', $reflectionClass->getName())
            );
        }

        $method = $reflectionClass->getMethod('handle');

        /** @var ReflectionParameter|null $parameter */
        $parameter = $method->getParameters()[0] ?? null;

        if ($parameter === null) {
            throw new InvalidArgumentException();
        }

        $type = $parameter->getType();

        if ($type === null) {
            throw new InvalidArgumentException();
        }

        $types = $type instanceof ReflectionUnionType ? $type->getTypes() : [$type];

        return array_map(
            fn(ReflectionNamedType $type): string => $type->getName(),
            $types
        );
    }
}
