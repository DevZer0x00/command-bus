<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\DependencyInjection\Compiler;

use DevZer0x00\CommandBus\BusInterface;
use DevZer0x00\CommandBus\ContainerBus;
use ReflectionClass;
use ReflectionParameter;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\TypedReference;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
            $class = $container->getParameterBag()->resolveValue($definition->getClass());
            /** @phpstan-ignore-next-line */
            $commandClass = $this->getCommandType($container->getReflectionClass($class));

            $handlerMap[$commandClass] = $id;
            $refMaps[$id] = new TypedReference($id, $class);
        }

        $id = EventDispatcherInterface::class;
        $refMaps[$id] = new Reference($id);

        $busDefinition = $container->getDefinition(BusInterface::class);
        $busDefinition->setArgument('$container', ServiceLocatorTagPass::register($container, $refMaps));
        $busDefinition->setArgument('$handlerMap', $handlerMap);
    }

    private function getCommandType(ReflectionClass $reflectionClass): string
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

        return $type->getName();
    }
}
