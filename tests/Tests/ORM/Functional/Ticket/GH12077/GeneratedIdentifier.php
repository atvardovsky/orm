<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket\GH12077;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;

class GeneratedIdentifier extends AbstractIdGenerator
{
    private static int $nextId = 12077;

    public function generateId(EntityManagerInterface $em, object|null $entity): int
    {
        return self::$nextId++;
    }
}
