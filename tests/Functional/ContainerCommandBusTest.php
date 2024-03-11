<?php

declare(strict_types=1);

namespace Tests\Functional;

use DevZer0x00\CommandBus\BusInterface;
use DevZer0x00\CommandBus\ContainerBus;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerCommandBusTest extends KernelTestCase
{
    protected ContainerInterface $container;

    protected function setUp(): void
    {
        $kernel = new TestKernel('test', true);
        $kernel->boot();
        $this->container = $kernel->getContainer();
    }

    public function testCommandBus()
    {
        $commandBus = $this->container->get(BusInterface::class);

        $this->assertInstanceOf(ContainerBus::class, $commandBus);
    }
}
