<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
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
            GH12555TargetEntity::class,
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
