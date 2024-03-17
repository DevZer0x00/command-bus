<?php

declare(strict_types=1);

namespace Tests\Unit\Wrapper;

use DevZer0x00\CommandBus\AbstractCommandBus;
use DevZer0x00\CommandBus\CommandInterface;
use DevZer0x00\CommandBus\CommandHandlerInterface;
use DevZer0x00\CommandBus\Wrapper\WrapperProcessorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class AbstractCommandBusTest extends TestCase
{
    public function testWrapped()
    {
        $handlerWrapper = $this->createMock(CommandHandlerInterface::class);

        $wrapperProcessor = $this->createMock(WrapperProcessorInterface::class);
        $wrapperProcessor->expects($this->once())
            ->method('wrap')
            ->willReturn($handlerWrapper);

        $commandBus = $this->getCommandBus($wrapperProcessor, $this->createMock(CommandHandlerInterface::class));
        $result = $commandBus->handle(new stdClass());

        $this->assertEquals(1, $result);
    }

    private function getCommandBus(
        WrapperProcessorInterface|MockObject $wrapperProcessor,
        CommandHandlerInterface $commandHandler,
    ): AbstractCommandBus {
        return new class($wrapperProcessor, $commandHandler) extends AbstractCommandBus {
            public function __construct(
                WrapperProcessorInterface $wrapperProcessor,
                private readonly CommandHandlerInterface $commandHandler
            ) {
                parent::__construct($wrapperProcessor);
            }

            protected function getHandler(mixed $command): CommandHandlerInterface
            {
                return $this->commandHandler;
            }
        };
    }
}
