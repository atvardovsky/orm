<?php
// phpcs:ignoreFile

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket\GH12077;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'gh12077_hooked_generated_identifier')]
class HookedGeneratedIdentifierEntity
{
    #[Id]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(class: GeneratedIdentifier::class)]
    #[Column(type: 'integer')]
    public int $id {
        get => $this->id;
        set => $this->id = $value;
    }
}

#[Entity]
#[Table(name: 'gh12077_hooked_post_insert_generated_identifier')]
class HookedPostInsertGeneratedIdentifierEntity
{
    #[Id]
    #[GeneratedValue(strategy: 'IDENTITY')]
    #[Column(type: 'integer')]
    public int $id {
        get => $this->id;
        set => $this->id = $value;
    }
}
