<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Version;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Doctrine\Tests\OrmFunctionalTestCase;
use PHPUnit\Framework\Attributes\Group;

use function preg_quote;

#[Group('GH12555')]
class GH12555Test extends OrmFunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createSchemaForModels(
            GH12555SimpleEntity::class,
            GH12555OwningEntity::class,
            GH12555NullableOwningEntity::class,
            GH12555TargetEntity::class,
            GH12555CompositeEntity::class,
            GH12555VersionedEntity::class,
            GH12555InheritanceRoot::class,
            GH12555InheritanceChild::class,
            GH12555EagerOwningEntity::class,
            GH12555FilteredEntity::class,
            GH12555EmbeddedEntity::class,
        );
    }

    public function testPartialLazyInitializationSelectsOnlyColumnsMissingFromPartialQuery(): void
    {
        $this->requireNativeLazyObjects();

        $entity = new GH12555SimpleEntity('loaded value', 'first missing value', 'second missing value');

        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->clear();

        $entity = $this->_em->createQuery(
            'SELECT PARTIAL e.{id, loadedField} FROM ' . GH12555SimpleEntity::class . ' e WHERE e.id = :id',
        )->setParameter('id', $entity->id)
            ->getSingleResult();

        self::assertTrue($this->_em->getUnitOfWork()->isUninitializedObject($entity));
        self::assertSame('loaded value', $entity->loadedField);

        $this->getQueryLog()->reset()->enable();

        self::assertSame('first missing value', $entity->firstMissingField);

        $this->assertQueryCount(1);

        $sql = $this->getLastLoggedQuery()['sql'];
        $this->assertSqlSelectsColumn('id', $sql);
        $this->assertSqlSelectsColumn('first_missing_field', $sql);
        $this->assertSqlSelectsColumn('second_missing_field', $sql);
        $this->assertSqlDoesNotSelectColumn('loaded_field', $sql);

        self::assertSame('second missing value', $entity->secondMissingField);
        self::assertFalse($this->_em->getUnitOfWork()->isUninitializedObject($entity));
    }

    public function testPartialLazyInitializationKeepsOwningToOneAssociation(): void
    {
        $this->requireNativeLazyObjects();

        $target = new GH12555TargetEntity('target value');
        $entity = new GH12555OwningEntity('loaded value', 'missing value', $target);

        $this->_em->persist($target);
        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->clear();

        $entity = $this->_em->createQuery(
            'SELECT PARTIAL e.{id, loadedField} FROM ' . GH12555OwningEntity::class . ' e WHERE e.id = :id',
        )->setParameter('id', $entity->id)
            ->getSingleResult();

        $this->getQueryLog()->reset()->enable();

        self::assertSame('missing value', $entity->missingField);

        $this->assertQueryCount(1);

        $sql = $this->getLastLoggedQuery()['sql'];
        $this->assertSqlSelectsColumn('id', $sql);
        $this->assertSqlSelectsColumn('missing_field', $sql);
        $this->assertSqlSelectsColumn('target_id', $sql);
        $this->assertSqlDoesNotSelectColumn('loaded_field', $sql);

        self::assertNotNull($entity->target);
        self::assertSame('target value', $entity->target->label);
    }

    public function testReferenceProxyStillUsesNormalFullLoad(): void
    {
        $this->requireNativeLazyObjects();

        $entity = new GH12555SimpleEntity('loaded value', 'first missing value', 'second missing value');

        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->clear();

        $reference = $this->_em->getReference(GH12555SimpleEntity::class, $entity->id);

        $this->getQueryLog()->reset()->enable();

        self::assertSame('first missing value', $reference->firstMissingField);

        $this->assertQueryCount(1);

        $sql = $this->getLastLoggedQuery()['sql'];
        $this->assertSqlSelectsColumn('id', $sql);
        $this->assertSqlSelectsColumn('loaded_field', $sql);
        $this->assertSqlSelectsColumn('first_missing_field', $sql);
        $this->assertSqlSelectsColumn('second_missing_field', $sql);
    }

    public function testPartialLazyInitializationSelectsEveryCompositeIdentifierColumn(): void
    {
        $this->requireNativeLazyObjects();

        $entity = new GH12555CompositeEntity('first id', 'second id', 'loaded value', 'missing value');

        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->clear();

        $entity = $this->_em->createQuery(
            'SELECT PARTIAL e.{firstId, secondId, loadedField} FROM '
            . GH12555CompositeEntity::class
            . ' e WHERE e.firstId = :firstId AND e.secondId = :secondId',
        )->setParameter('firstId', 'first id')
            ->setParameter('secondId', 'second id')
            ->getSingleResult();

        $this->getQueryLog()->reset()->enable();

        self::assertSame('missing value', $entity->missingField);

        $this->assertQueryCount(1);

        $sql = $this->getLastLoggedQuery()['sql'];
        $this->assertSqlSelectsColumn('first_id', $sql);
        $this->assertSqlSelectsColumn('second_id', $sql);
        $this->assertSqlSelectsColumn('missing_field', $sql);
        $this->assertSqlDoesNotSelectColumn('loaded_field', $sql);
    }

    public function testLoadedVersionFieldIsNotReselectedDuringPartialLazyInitialization(): void
    {
        $this->requireNativeLazyObjects();

        $entity = new GH12555VersionedEntity('loaded value', 'missing value');

        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->clear();

        $entity = $this->_em->createQuery(
            'SELECT PARTIAL e.{id, loadedField, lockVersion} FROM '
            . GH12555VersionedEntity::class
            . ' e WHERE e.id = :id',
        )->setParameter('id', $entity->id)
            ->getSingleResult();

        $this->getQueryLog()->reset()->enable();

        self::assertSame('missing value', $entity->missingField);

        $this->assertQueryCount(1);

        $sql = $this->getLastLoggedQuery()['sql'];
        $this->assertSqlSelectsColumn('id', $sql);
        $this->assertSqlSelectsColumn('missing_field', $sql);
        $this->assertSqlDoesNotSelectColumn('loaded_field', $sql);
        $this->assertSqlDoesNotSelectColumn('lock_version', $sql);
    }

    public function testMissingVersionFieldIsSelectedDuringPartialLazyInitialization(): void
    {
        $this->requireNativeLazyObjects();

        $entity = new GH12555VersionedEntity('loaded value', 'missing value');

        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->clear();

        $entity = $this->_em->createQuery(
            'SELECT PARTIAL e.{id, loadedField} FROM ' . GH12555VersionedEntity::class . ' e WHERE e.id = :id',
        )->setParameter('id', $entity->id)
            ->getSingleResult();

        $this->getQueryLog()->reset()->enable();

        self::assertSame('missing value', $entity->missingField);

        $this->assertQueryCount(1);

        $sql = $this->getLastLoggedQuery()['sql'];
        $this->assertSqlSelectsColumn('id', $sql);
        $this->assertSqlSelectsColumn('missing_field', $sql);
        $this->assertSqlSelectsColumn('lock_version', $sql);
        $this->assertSqlDoesNotSelectColumn('loaded_field', $sql);
    }

    public function testInheritancePartialLazyInitializationUsesFullLoadFallback(): void
    {
        $this->requireNativeLazyObjects();

        $entity = new GH12555InheritanceChild('loaded value', 'missing value');

        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->clear();

        $entity = $this->_em->createQuery(
            'SELECT PARTIAL e.{id, rootLoadedField} FROM '
            . GH12555InheritanceChild::class
            . ' e WHERE e.id = :id',
        )->setParameter('id', $entity->id)
            ->getSingleResult();

        $this->getQueryLog()->reset()->enable();

        self::assertSame('missing value', $entity->childMissingField);

        $this->assertQueryCount(1);

        $sql = $this->getLastLoggedQuery()['sql'];
        $this->assertSqlSelectsColumn('root_loaded_field', $sql);
        $this->assertSqlSelectsColumn('child_missing_field', $sql);
    }

    public function testEagerToOnePartialLazyInitializationUsesFullLoadFallback(): void
    {
        $this->requireNativeLazyObjects();

        $target = new GH12555TargetEntity('target value');
        $entity = new GH12555EagerOwningEntity('loaded value', 'missing value', $target);

        $this->_em->persist($target);
        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->clear();

        $entity = $this->_em->createQuery(
            'SELECT PARTIAL e.{id, loadedField} FROM ' . GH12555EagerOwningEntity::class . ' e WHERE e.id = :id',
        )->setParameter('id', $entity->id)
            ->getSingleResult();

        $this->getQueryLog()->reset()->enable();

        self::assertSame('missing value', $entity->missingField);

        $this->assertQueryCount(1);

        $sql = $this->getLastLoggedQuery()['sql'];
        $this->assertSqlSelectsColumn('loaded_field', $sql);
        $this->assertSqlSelectsColumn('missing_field', $sql);
        $this->assertSqlSelectsColumn('target_id', $sql);
        self::assertSame('target value', $entity->target->label);
    }

    public function testRestrictedPartialLazyInitializationKeepsSqlFilter(): void
    {
        $this->requireNativeLazyObjects();

        $entity = new GH12555FilteredEntity('loaded value', 'missing value');

        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->clear();

        $this->_em->getConfiguration()->addFilter('gh12555_visible', GH12555VisibleFilter::class);
        $this->_em->getFilters()->enable('gh12555_visible');

        $entity = $this->_em->createQuery(
            'SELECT PARTIAL e.{id, loadedField} FROM ' . GH12555FilteredEntity::class . ' e WHERE e.id = :id',
        )->setParameter('id', $entity->id)
            ->getSingleResult();

        $this->getQueryLog()->reset()->enable();

        self::assertSame('missing value', $entity->missingField);

        $this->assertQueryCount(1);

        $sql = $this->getLastLoggedQuery()['sql'];
        $this->assertSqlSelectsColumn('id', $sql);
        $this->assertSqlSelectsColumn('visible', $sql);
        $this->assertSqlSelectsColumn('missing_field', $sql);
        $this->assertSqlDoesNotSelectColumn('loaded_field', $sql);
        self::assertStringContainsString('.visible = 1', $sql);
    }

    public function testPartialLazyInitializationKeepsNullableOwningToOneAssociationNull(): void
    {
        $this->requireNativeLazyObjects();

        $entity = new GH12555NullableOwningEntity('loaded value', 'missing value');

        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->clear();

        $entity = $this->_em->createQuery(
            'SELECT PARTIAL e.{id, loadedField} FROM ' . GH12555NullableOwningEntity::class . ' e WHERE e.id = :id',
        )->setParameter('id', $entity->id)
            ->getSingleResult();

        $this->getQueryLog()->reset()->enable();

        self::assertSame('missing value', $entity->missingField);

        $this->assertQueryCount(1);

        $sql = $this->getLastLoggedQuery()['sql'];
        $this->assertSqlSelectsColumn('id', $sql);
        $this->assertSqlSelectsColumn('missing_field', $sql);
        $this->assertSqlSelectsColumn('target_id', $sql);
        $this->assertSqlDoesNotSelectColumn('loaded_field', $sql);

        self::assertNull($entity->target);
    }

    public function testPartialLazyInitializationSelectsOnlyMissingEmbeddableColumns(): void
    {
        $this->requireNativeLazyObjects();

        $entity = new GH12555EmbeddedEntity(
            'entity name',
            new GH12555EmbeddedValue('loaded embedded value', 'missing embedded value'),
        );

        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->clear();

        $entity = $this->_em->createQuery(
            'SELECT PARTIAL e.{id, embedded.loadedField} FROM '
            . GH12555EmbeddedEntity::class
            . ' e WHERE e.id = :id',
        )->setParameter('id', $entity->id)
            ->getSingleResult();

        $this->getQueryLog()->reset()->enable();

        self::assertSame('missing embedded value', $entity->embedded->missingField);

        $this->assertQueryCount(1);

        $sql = $this->getLastLoggedQuery()['sql'];
        $this->assertSqlSelectsColumn('id', $sql);
        $this->assertSqlSelectsColumn('name', $sql);
        $this->assertSqlSelectsColumn('embedded_missing_field', $sql);
        $this->assertSqlDoesNotSelectColumn('embedded_loaded_field', $sql);
    }

    public function testPartialLazyInitializationDoesNotPolluteSubsequentFullLoads(): void
    {
        $this->requireNativeLazyObjects();

        $entity = new GH12555SimpleEntity('loaded value', 'first missing value', 'second missing value');

        $this->_em->persist($entity);
        $this->_em->flush();
        $this->_em->clear();

        $partial = $this->_em->createQuery(
            'SELECT PARTIAL e.{id, loadedField} FROM ' . GH12555SimpleEntity::class . ' e WHERE e.id = :id',
        )->setParameter('id', $entity->id)
            ->getSingleResult();

        self::assertSame('first missing value', $partial->firstMissingField);

        $this->_em->clear();
        $this->getQueryLog()->reset()->enable();

        self::assertInstanceOf(GH12555SimpleEntity::class, $this->_em->find(GH12555SimpleEntity::class, $entity->id));

        $this->assertQueryCount(1);

        $sql = $this->getLastLoggedQuery()['sql'];
        $this->assertSqlSelectsColumn('id', $sql);
        $this->assertSqlSelectsColumn('loaded_field', $sql);
        $this->assertSqlSelectsColumn('first_missing_field', $sql);
        $this->assertSqlSelectsColumn('second_missing_field', $sql);
    }

    private function requireNativeLazyObjects(): void
    {
        if (! $this->_em->getConfiguration()->isNativeLazyObjectsEnabled()) {
            self::markTestSkipped('Test requires native lazy objects to be enabled.');
        }
    }

    private function assertSqlSelectsColumn(string $columnName, string $sql): void
    {
        self::assertMatchesRegularExpression($this->selectColumnPattern($columnName), $sql);
    }

    private function assertSqlDoesNotSelectColumn(string $columnName, string $sql): void
    {
        self::assertDoesNotMatchRegularExpression($this->selectColumnPattern($columnName), $sql);
    }

    private function selectColumnPattern(string $columnName): string
    {
        return '/\.' . preg_quote($columnName, '/') . '\s+AS\s+/i';
    }
}

#[Entity]
#[Table(name: 'gh12555_simple')]
class GH12555SimpleEntity
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

#[Entity]
#[Table(name: 'gh12555_owning')]
class GH12555OwningEntity
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    public int|null $id = null;

    #[Column(name: 'loaded_field', type: 'string')]
    public string $loadedField;

    #[Column(name: 'missing_field', type: 'string')]
    public string $missingField;

    #[ManyToOne(targetEntity: GH12555TargetEntity::class)]
    #[JoinColumn(name: 'target_id', referencedColumnName: 'id', nullable: false)]
    public GH12555TargetEntity $target;

    public function __construct(string $loadedField, string $missingField, GH12555TargetEntity $target)
    {
        $this->loadedField  = $loadedField;
        $this->missingField = $missingField;
        $this->target       = $target;
    }
}

#[Entity]
#[Table(name: 'gh12555_nullable_owning')]
class GH12555NullableOwningEntity
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    public int|null $id = null;

    #[Column(name: 'loaded_field', type: 'string')]
    public string $loadedField;

    #[Column(name: 'missing_field', type: 'string')]
    public string $missingField;

    #[ManyToOne(targetEntity: GH12555TargetEntity::class)]
    #[JoinColumn(name: 'target_id', referencedColumnName: 'id', nullable: true)]
    public GH12555TargetEntity|null $target = null;

    public function __construct(string $loadedField, string $missingField)
    {
        $this->loadedField  = $loadedField;
        $this->missingField = $missingField;
    }
}

#[Entity]
#[Table(name: 'gh12555_target')]
class GH12555TargetEntity
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    public int|null $id = null;

    #[Column(type: 'string')]
    public string $label;

    public function __construct(string $label)
    {
        $this->label = $label;
    }
}

#[Entity]
#[Table(name: 'gh12555_composite')]
class GH12555CompositeEntity
{
    #[Id]
    #[Column(name: 'first_id', type: 'string')]
    public string $firstId;

    #[Id]
    #[Column(name: 'second_id', type: 'string')]
    public string $secondId;

    #[Column(name: 'loaded_field', type: 'string')]
    public string $loadedField;

    #[Column(name: 'missing_field', type: 'string')]
    public string $missingField;

    public function __construct(string $firstId, string $secondId, string $loadedField, string $missingField)
    {
        $this->firstId      = $firstId;
        $this->secondId     = $secondId;
        $this->loadedField  = $loadedField;
        $this->missingField = $missingField;
    }
}

#[Entity]
#[Table(name: 'gh12555_versioned')]
class GH12555VersionedEntity
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    public int|null $id = null;

    #[Column(name: 'loaded_field', type: 'string')]
    public string $loadedField;

    #[Column(name: 'missing_field', type: 'string')]
    public string $missingField;

    #[Column(name: 'lock_version', type: 'integer')]
    #[Version]
    public int $lockVersion = 1;

    public function __construct(string $loadedField, string $missingField)
    {
        $this->loadedField  = $loadedField;
        $this->missingField = $missingField;
    }
}

#[Entity]
#[InheritanceType('SINGLE_TABLE')]
#[DiscriminatorColumn(name: 'discr', type: 'string')]
#[DiscriminatorMap(['child' => GH12555InheritanceChild::class])]
#[Table(name: 'gh12555_inheritance')]
class GH12555InheritanceRoot
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    public int|null $id = null;

    #[Column(name: 'root_loaded_field', type: 'string')]
    public string $rootLoadedField;

    public function __construct(string $rootLoadedField)
    {
        $this->rootLoadedField = $rootLoadedField;
    }
}

#[Entity]
class GH12555InheritanceChild extends GH12555InheritanceRoot
{
    #[Column(name: 'child_missing_field', type: 'string')]
    public string $childMissingField;

    public function __construct(string $rootLoadedField, string $childMissingField)
    {
        parent::__construct($rootLoadedField);

        $this->childMissingField = $childMissingField;
    }
}

#[Entity]
#[Table(name: 'gh12555_eager_owning')]
class GH12555EagerOwningEntity
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    public int|null $id = null;

    #[Column(name: 'loaded_field', type: 'string')]
    public string $loadedField;

    #[Column(name: 'missing_field', type: 'string')]
    public string $missingField;

    #[ManyToOne(targetEntity: GH12555TargetEntity::class, fetch: 'EAGER')]
    #[JoinColumn(name: 'target_id', referencedColumnName: 'id', nullable: false)]
    public GH12555TargetEntity $target;

    public function __construct(string $loadedField, string $missingField, GH12555TargetEntity $target)
    {
        $this->loadedField  = $loadedField;
        $this->missingField = $missingField;
        $this->target       = $target;
    }
}

#[Entity]
#[Table(name: 'gh12555_filtered')]
class GH12555FilteredEntity
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    public int|null $id = null;

    #[Column(name: 'loaded_field', type: 'string')]
    public string $loadedField;

    #[Column(name: 'missing_field', type: 'string')]
    public string $missingField;

    #[Column(type: 'boolean')]
    public bool $visible = true;

    public function __construct(string $loadedField, string $missingField)
    {
        $this->loadedField  = $loadedField;
        $this->missingField = $missingField;
    }
}

class GH12555VisibleFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string
    {
        if ($targetEntity->name !== GH12555FilteredEntity::class) {
            return '';
        }

        return $targetTableAlias . '.visible = 1';
    }
}

#[Entity]
#[Table(name: 'gh12555_embedded_entity')]
class GH12555EmbeddedEntity
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    public int|null $id = null;

    #[Column(type: 'string')]
    public string $name;

    #[Embedded(class: GH12555EmbeddedValue::class, columnPrefix: false)]
    public GH12555EmbeddedValue $embedded;

    public function __construct(string $name, GH12555EmbeddedValue $embedded)
    {
        $this->name     = $name;
        $this->embedded = $embedded;
    }
}

#[Embeddable]
class GH12555EmbeddedValue
{
    #[Column(name: 'embedded_loaded_field', type: 'string')]
    public string $loadedField;

    #[Column(name: 'embedded_missing_field', type: 'string')]
    public string $missingField;

    public function __construct(string $loadedField, string $missingField)
    {
        $this->loadedField  = $loadedField;
        $this->missingField = $missingField;
    }
}
