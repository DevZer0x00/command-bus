<?php

declare(strict_types=1);

namespace Tests\unit\Wrapper;

use DevZer0x00\CommandBus\AbstractCommandBus;
use DevZer0x00\CommandBus\CommandHandlerInterface;
use DevZer0x00\CommandBus\Wrapper\HandlerWrapperInterface;
use DevZer0x00\CommandBus\Wrapper\WrapperProcessorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AbstractCommandBusTest extends TestCase
{
    public function testWrapped()
    {
        $handlerWrapper = $this->createMock(HandlerWrapperInterface::class);
        $handlerWrapper->expects($this->once())
            ->method('handle')
            ->willReturn(1);
        $handlerWrapper->expects($this->once())
            ->method('preHandle');
        $handlerWrapper->expects($this->once())
            ->method('postHandle');

        $wrapperProcessor = $this->createMock(WrapperProcessorInterface::class);
        $wrapperProcessor->expects($this->once())
            ->method('wrap')
            ->willReturn($handlerWrapper);

        $commandBus = $this->getCommandBus($wrapperProcessor, $this->createMock(CommandHandlerInterface::class));
        $result = $commandBus->handle(new class {
        });

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
