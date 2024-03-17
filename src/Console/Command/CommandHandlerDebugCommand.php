<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Console\Command;

use DevZer0x00\CommandBus\Attribute\DoctrineORMTransactionalWrapper;
use DevZer0x00\CommandBus\Wrapper\CommandHandlerWrapperFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionAttribute;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Command\BuildDebugContainerTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use function array_map;
use function array_search;
use function implode;

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
        $wrapperAttributes = $this->loadWrapperAttributes($object);

        $table = new Table($output);

        $table->setHeaderTitle('Command Handlers');
        $table->setHeaders(['ID', 'Class', 'Command', 'Wrapper Attributes', 'Info']);

        $rows = [];

        foreach ($handlers as $id => $tags) {
            $definition = $object->getDefinition($id);
            $handlerClass = $object->getParameterBag()->resolveValue($definition->getClass());
            $handlerReflection = $object->getReflectionClass($handlerClass);

            $id = $handlerClass === $id ? "<info>same as class</info>" : "<info>{$id}</info>";
            $class = "<comment>{$handlerClass}</comment>";
            $command = "";

            $attributesMap = array_map(
                fn(ReflectionAttribute $attribute) => $attribute->getName(),
                $object->getReflectionClass($handlerClass)->getAttributes()
            );
            $attributesMap = array_intersect($wrapperAttributes, $attributesMap);

            $attributes = $info = [];

            foreach ($attributesMap as $attributeClass) {
                $attributeReflection = $object->getReflectionClass($attributeClass);
                $attributes[] = $attributeReflection->getShortName();
            }

            if ($this->notHasEntityManagerWithoutTransactionWrapper($handlerReflection, $attributesMap)) {
                $info[] = '<error>Check transaction wrapper</error>';
            }

            $rows[] = [
                $id,
                $class,
                $command,
                implode(',', $attributes),
                implode(',', $info),
            ];
        }

        $table->setRows($rows);
        $table->render();

        return 0;
    }

    private function notHasEntityManagerWithoutTransactionWrapper(
        ReflectionClass $handlerReflection,
        array $attributesMap
    ): bool {
        $constructorMethod = $handlerReflection->getConstructor();
        $parameters = $constructorMethod->getParameters();

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type === null) {
                continue;
            }

            $class = $parameter->getType()->getName();

            $hasEntityManager = $class instanceof EntityManagerInterface || $class === EntityManagerInterface::class;

            if ($hasEntityManager) {
                break;
            }
        }

        return $hasEntityManager && array_search(DoctrineORMTransactionalWrapper::class, $attributesMap) === false;
    }

    private function loadWrapperAttributes(ContainerBuilder $object): array
    {
        $wrappers = $object->findTaggedServiceIds('app.command_handler.wrapper_factory');

        $result = [];

        foreach ($wrappers as $id => $tags) {
            $definition = $object->getDefinition($id);
            /** @var CommandHandlerWrapperFactoryInterface $class */
            $class = $object->getParameterBag()->resolveValue($definition->getClass());
            $result[] = $class::getDefaultAttributeName();
        }

        return $result;
    }
}
