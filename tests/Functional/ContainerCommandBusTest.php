<?php

declare(strict_types=1);

namespace Tests\Functional;

use DevZer0x00\CommandBus\BusInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerCommandBusTest extends KernelTestCase
{
    protected ContainerInterface $container;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = new TestKernel('test', true);
        $kernel->boot();
        $this->container = $kernel->getContainer();
    }

    public function test1()
    {
        $commandBus = $this->container->get(BusInterface::class);
    }
}
