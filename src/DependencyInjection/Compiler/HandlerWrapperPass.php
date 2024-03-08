<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\DependencyInjection\Compiler;

use DevZer0x00\CommandBus\BusInterface;
use DevZer0x00\CommandBus\ContainerBus;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\TypedReference;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class HandlerWrapperPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('app.command_handler.wrapper_factory');

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
            ->register(BusInterface::class, ContainerBus::class)
            ->setAutowired(false)
            ->setPublic(true)
            ->setArguments([ServiceLocatorTagPass::register($container, $refMaps), $handlerMap]);
    }
}
