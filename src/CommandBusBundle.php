<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus;

use CommandBusBundle\Command\CommandHandlerInterface;
use CommandBusBundle\DepencyInjection\CommandBusExtension;
use CommandBusBundle\DepencyInjection\Compiler\CommandHandlerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class CommandBusBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container
            ->addCompilerPass(new CommandHandlerPass())
            ->registerForAutoconfiguration(CommandHandlerInterface::class)
            ->addTag('app.command_handler');
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new CommandBusExtension();
    }
}
