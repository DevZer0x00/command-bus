<?php

declare(strict_types=1);

namespace Tests\Unit\Wrapper\Transaction;

use DevZer0x00\CommandBus\Attribute\DoctrineTransactionalWrapper;
use DevZer0x00\CommandBus\Wrapper\Transaction\DoctrineTransactionHandlerWrapperFactory;
use PHPUnit\Framework\TestCase;

class DoctrineTransactionHandlerWrapperFactoryTest extends TestCase
{
    public function testGetDefaultAttributeName()
    {
        $this->assertEquals(
            DoctrineTransactionalWrapper::class,
            DoctrineTransactionHandlerWrapperFactory::getDefaultAttributeName()
        );
    }
}
