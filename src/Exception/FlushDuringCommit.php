<?php

declare(strict_types=1);

namespace Doctrine\ORM\Exception;

use LogicException;

final class FlushDuringCommit extends LogicException implements ORMException
{
    public static function create(): self
    {
        return new self(
            'A flush operation is already in progress. Doctrine ORM does not support calling ' .
            'EntityManager::flush() from event listeners dispatched by EntityManager::flush().',
        );
    }
}
