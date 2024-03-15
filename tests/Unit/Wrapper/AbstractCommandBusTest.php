<?php

declare(strict_types=1);

namespace Tests\Unit\Wrapper;

use DevZer0x00\CommandBus\AbstractBus;
use DevZer0x00\CommandBus\CommandInterface;
use DevZer0x00\CommandBus\HandlerInterface;
use DevZer0x00\CommandBus\Wrapper\WrapperProcessorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AbstractCommandBusTest extends TestCase
{
    public function testWrapped()
    {
        $handlerWrapper = $this->createMock(HandlerInterface::class);
        $handlerWrapper->expects($this->once())
            ->method('handle')
            ->willReturn(1);

        $wrapperProcessor = $this->createMock(WrapperProcessorInterface::class);
        $wrapperProcessor->expects($this->once())
            ->method('wrap')
            ->willReturn($handlerWrapper);

        $commandBus = $this->getCommandBus($wrapperProcessor, $this->createMock(HandlerInterface::class));
        $result = $commandBus->handle(new class implements CommandInterface {
        });

        $this->assertEquals(1, $result);
    }

    private function getCommandBus(
        WrapperProcessorInterface|MockObject $wrapperProcessor,
        HandlerInterface $commandHandler,
    ): AbstractBus {
        return new class($wrapperProcessor, $commandHandler) extends AbstractBus {
            public function __construct(
                WrapperProcessorInterface $wrapperProcessor,
                private readonly HandlerInterface $commandHandler
            ) {
                parent::__construct($wrapperProcessor);
            }

            protected function getHandler(mixed $command): HandlerInterface
            {
                return $this->commandHandler;
            }
        };
    }
}
