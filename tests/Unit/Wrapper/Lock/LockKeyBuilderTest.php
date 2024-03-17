<?php

declare(strict_types=1);

namespace Tests\Unit\Wrapper\Lock;

use DevZer0x00\CommandBus\Attribute\LockWrapper;
use DevZer0x00\CommandBus\Wrapper\Lock\LockKeyBuilder;
use PHPUnit\Framework\TestCase;

use stdClass;

use function json_encode;
use function md5;

class LockKeyBuilderTest extends TestCase
{
    public function testByCommandFields()
    {
        $attribute = new LockWrapper(commandFields: ['i', 'k']);

        $commandObj = new stdClass();

        $commandObj->i = 'test';
        $commandObj->j = 'test1';
        $commandObj->k = 'test2';

        $keyBuilder = new LockKeyBuilder($attribute, $commandObj::class);

        $this->assertEquals(
            $this->buildKeyFromStrings($commandObj, ['test', 'test2']),
            $keyBuilder->build($commandObj)
        );
    }

    public function testByLockKey()
    {
        $attribute = new LockWrapper(commandFields: ['i', 'k'], lockKey: 'test');

        $commandObj = new stdClass();

        $commandObj->i = 'test';
        $commandObj->j = 'test1';
        $commandObj->k = 'test2';

        $keyBuilder = new LockKeyBuilder($attribute, $commandObj::class);

        $this->assertEquals('test', $keyBuilder->build($commandObj));
    }

    private function buildKeyFromStrings(object $class, array $strings): string
    {
        return md5($class::class . json_encode($strings));
    }
}
