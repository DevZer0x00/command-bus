<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Console\Command;

use Symfony\Bundle\FrameworkBundle\Command\BuildDebugContainerTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'debug:command-handler')]
class CommandHandlerDebugCommand extends Command
{
    use BuildDebugContainerTrait;

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $kernel = $this->getApplication()->getKernel();
        $object = $this->getContainerBuilder($kernel);

        $handlers = $object->findTaggedServiceIds('app.command_handler');

        $table = new Table($output);

        $table->setHeaderTitle('Command Handlers');
        $table->setHeaders(['ID', 'Class', 'Command']);

        $rows = [];

        foreach ($handlers as $id => $tags) {
            $definition = $object->getDefinition($id);
            $class = $object->getParameterBag()->resolveValue($definition->getClass());

            $rows[] = ["<info>{$id}</info>", "<comment>{$class}</comment>", ''];
        }

        $table->setRows($rows);
        $table->render();

        return 0;
    }
}
