<?php

declare(strict_types=1);

namespace DevZer0x00\CommandBus\Wrapper\Lock;

use DevZer0x00\CommandBus\Attribute\LockWrapper;

use function json_encode;
use function md5;

readonly class LockKeyBuilder implements LockKeyBuilderInterface
{
    public function __construct(
        private LockWrapper $attribute,
        private string $originalClass,
    ) {
    }

    public function build($commandObject): string
    {
        $params = [];

        foreach ($this->attribute->commandFields as $field) {
            $params[] = $commandObject->$field;
        }

        $key = $this->attribute->lockKey;
        $key ??= $this->buildLockKey($params);

        return $key;
    }

    private function buildLockKey(array $params): string
    {
        $json = json_encode($params);

        return md5($this->originalClass . $json);
    }
}
