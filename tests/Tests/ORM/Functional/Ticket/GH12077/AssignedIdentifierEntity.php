<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket\GH12077;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'gh12077_assigned_identifier')]
class AssignedIdentifierEntity
{
    #[Id]
    #[GeneratedValue(strategy: 'NONE')]
    #[Column(type: 'integer')]
    public int $id;
}
