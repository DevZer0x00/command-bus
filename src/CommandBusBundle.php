<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus;

use DevZer0x00\CommandBus\DependencyInjection\Compiler\CommandHandlerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
}
