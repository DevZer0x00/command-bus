<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Lock;

use DevZer0x00\CommandBus\Attribute\LockWrapper;

use DevZer0x00\CommandBus\CommandInterface;

use function json_encode;
use function md5;

readonly class LockKeyBuilder implements LockKeyBuilderInterface
{
    public function __construct(
        private LockWrapper $attribute,
        private string $originalClass,
    ) {
    }

    public function build(object $command): string
    {
        $params = [];

        foreach ($this->attribute->commandFields as $field) {
            $params[] = $command->$field;
        }

        $key = $this->attribute->lockKey;
        $key ??= $this->buildLockKey($params);

        return $key;
    }

    /**
     * @param array<mixed> $params
     */
    private function buildLockKey(array $params): string
    {
        $json = json_encode($params);

        return md5($this->originalClass . $json);
    }
}
