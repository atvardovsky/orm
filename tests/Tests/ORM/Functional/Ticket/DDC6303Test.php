<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Doctrine\Persistence\Mapping\MappingException;
use Doctrine\Tests\OrmFunctionalTestCase;
use PHPUnit\Framework\Attributes\Group;

use function array_keys;
use function array_walk;
use function count;

#[Group('#6303')]
class DDC6303Test extends OrmFunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createSchemaForModels(
            DDC6303BaseClass::class,
            DDC6303ChildA::class,
            DDC6303ChildB::class,
        );
    }

    public function testMixedTypeHydratedCorrectlyInJoinedInheritance(): void
    {
        // DDC6303ChildA and DDC6303ChildB have an inheritance from DDC6303BaseClass,
        // but one has a string originalData and the second has an array, since the fields
        // are mapped differently
        $this->assertHydratedEntitiesSameToPersistedOnes([
            'a' => new DDC6303ChildA('a', 'authorized'),
            'b' => new DDC6303ChildB('b', ['accepted', 'authorized']),
        ]);
    }

    public function testEmptyValuesInJoinedInheritance(): void
    {
        $this->assertHydratedEntitiesSameToPersistedOnes([
            'stringEmpty' => new DDC6303ChildA('stringEmpty', ''),
            'stringZero'  => new DDC6303ChildA('stringZero', 0),
            'arrayEmpty'  => new DDC6303ChildB('arrayEmpty', []),
        ]);
    }

    public function testSiblingFieldMappingsRemainDistinctWhenPersisterColumnsAreRegenerated(): void
    {
        $persistedEntities = [
            'regenerated-a' => new DDC6303ChildA('regenerated-a', 'authorized'),
            'regenerated-b' => new DDC6303ChildB('regenerated-b', ['accepted', 'authorized']),
        ];

        array_walk($persistedEntities, [$this->_em, 'persist']);
        $this->_em->flush();
        $this->_em->clear();

        $this->assertRepositoryContainsSameEntities($persistedEntities);

        $this->_em->clear();
        $this->_em->getConfiguration()->addFilter('ddc_6303_filter', DDC6303Filter::class);
        $this->_em->getFilters()->enable('ddc_6303_filter');

        $this->assertRepositoryContainsSameEntities($persistedEntities);
    }

    /**
     * @param DDC6303BaseClass[] $persistedEntities indexed by identifier
     *
     * @throws MappingException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function assertHydratedEntitiesSameToPersistedOnes(array $persistedEntities): void
    {
        array_walk($persistedEntities, [$this->_em, 'persist']);
        $this->_em->flush();
        $this->_em->clear();

        /** @var DDC6303BaseClass[] $entities */
        $entities = $this
            ->_em
            ->getRepository(DDC6303BaseClass::class)
            ->createQueryBuilder('p')
            ->where('p.id IN(:ids)')
            ->setParameter('ids', array_keys($persistedEntities))
            ->getQuery()->getResult();

        self::assertCount(count($persistedEntities), $entities);

        foreach ($entities as $entity) {
            $persistedEntity = $persistedEntities[$entity->id];

            self::assertEquals($entity, $persistedEntity);
            self::assertEquals($persistedEntity->getOriginalData(), $entity->getOriginalData());
        }
    }

    /** @param DDC6303BaseClass[] $persistedEntities indexed by identifier */
    private function assertRepositoryContainsSameEntities(array $persistedEntities): void
    {
        $entities = $this->_em->getRepository(DDC6303BaseClass::class)->findBy([
            'id' => array_keys($persistedEntities),
        ]);

        self::assertCount(count($persistedEntities), $entities);

        foreach ($entities as $entity) {
            self::assertEquals($entity, $persistedEntities[$entity->id]);
        }
    }
}

final class DDC6303Filter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string
    {
        return '';
    }
}

/**
 * Note: discriminator map order *IS IMPORTANT* for this test
 */
#[Table]
#[Entity]
#[InheritanceType('JOINED')]
#[DiscriminatorColumn(name: 'discr', type: 'string')]
#[DiscriminatorMap([DDC6303ChildB::class => DDC6303ChildB::class, DDC6303ChildA::class => DDC6303ChildA::class])]
abstract class DDC6303BaseClass
{
    /** @var string */
    #[Id]
    #[Column(type: 'string', length: 255)]
    #[GeneratedValue(strategy: 'NONE')]
    public $id;

    abstract public function getOriginalData(): mixed;
}

#[Table]
#[Entity]
class DDC6303ChildA extends DDC6303BaseClass
{
    public function __construct(
        string $id,
        #[Column(type: 'string', length: 255)]
        private mixed $originalData,
    ) {
        $this->id = $id;
    }

    public function getOriginalData(): mixed
    {
        return $this->originalData;
    }
}

#[Table]
#[Entity]
class DDC6303ChildB extends DDC6303BaseClass
{
    /** @param mixed[] $originalData */
    public function __construct(
        string $id,
        #[Column(type: 'simple_array', nullable: true)]
        private array $originalData,
    ) {
        $this->id = $id;
    }

    /** @return mixed[] */
    public function getOriginalData(): array
    {
        return $this->originalData;
    }
}
