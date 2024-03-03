<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\DependencyInjection\Compiler;

use DevZer0x00\CommandBus\CommandBusInterface;
use DevZer0x00\CommandBus\ContainerCommandBus;
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
            $commandClass = $this->getCommandType($container->getReflectionClass($class));

            $handlerMap[$commandClass] = $id;

            $refMaps[$id] = new TypedReference($id, $class);
        }

        $id = EventDispatcherInterface::class;
        $refMaps[$id] = new Reference($id);

        $container
            ->register(CommandBusInterface::class, ContainerCommandBus::class)
            ->setAutowired(false)
            ->setPublic(true)
            ->setArguments([ServiceLocatorTagPass::register($container, $refMaps), $handlerMap]);
    }

    private function getCommandType(ReflectionClass $reflClass): string
    {
        if (!$reflClass->hasMethod('handle')) {
            throw new InvalidArgumentException(
                sprintf('Command Handler %s does not have method handle', $reflClass->getName())
            );
        }

        $method = $reflClass->getMethod('handle');

        /** @var ReflectionParameter $parameter */
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
