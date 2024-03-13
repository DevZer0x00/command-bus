<?php

namespace DevZer0x00\CommandBus\Wrapper\Transaction;

use Doctrine\DBAL\Driver\Connection;

interface DoctrineTransactionStateCheckerInterface
{
    public function inTransaction(Connection $connection): bool;
}
