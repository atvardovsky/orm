<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\Tests\OrmFunctionalTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('GH12555')]
class GH12555SecondLevelCacheTest extends OrmFunctionalTestCase
{
    protected function setUp(): void
    {
        $this->enableSecondLevelCache();

        parent::setUp();

        $this->createSchemaForModels(GH12555CachedEntity::class);
    }

    public function testPartialLazyInitializationDoesNotStoreLocallyChangedLoadedFieldsInSecondLevelCache(): void
    {
        if (! $this->_em->getConfiguration()->isNativeLazyObjectsEnabled()) {
            self::markTestSkipped('Test requires native lazy objects to be enabled.');
        }

        $entity = new GH12555CachedEntity('database value', 'first missing value', 'second missing value');

        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->clear();
        $this->_em->getCache()->evictEntityRegion(GH12555CachedEntity::class);

        $partial = $this->_em->createQuery(
            'SELECT PARTIAL e.{id, loadedField} FROM ' . GH12555CachedEntity::class . ' e WHERE e.id = :id',
        )->setParameter('id', $entity->id)
            ->getSingleResult();

        $partial->loadedField = 'locally changed value';

        self::assertSame('first missing value', $partial->firstMissingField);

        $this->_em->clear();

        $reloaded = $this->_em->find(GH12555CachedEntity::class, $entity->id);

        self::assertInstanceOf(GH12555CachedEntity::class, $reloaded);
        self::assertSame('database value', $reloaded->loadedField);
    }
}

#[Entity]
#[Cache]
#[Table(name: 'gh12555_cached')]
class GH12555CachedEntity
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    public int|null $id = null;

    #[Column(name: 'loaded_field', type: 'string')]
    public string $loadedField;

    #[Column(name: 'first_missing_field', type: 'string')]
    public string $firstMissingField;

    #[Column(name: 'second_missing_field', type: 'string')]
    public string $secondMissingField;

    public function __construct(string $loadedField, string $firstMissingField, string $secondMissingField)
    {
        $this->loadedField        = $loadedField;
        $this->firstMissingField  = $firstMissingField;
        $this->secondMissingField = $secondMissingField;
    }
}
