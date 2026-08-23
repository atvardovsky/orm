<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Tools\SchemaValidator;
use Doctrine\Tests\OrmFunctionalTestCase;
use PHPUnit\Framework\Attributes\Group;

#[Group('GH12417')]
class GH12417Test extends OrmFunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createSchemaForModels(
            GH12417Level0::class,
            GH12417Level1::class,
            GH12417Level2::class,
            GH12417Item::class,
            GH12417Tag::class,
        );
    }

    public function testSchemaValidatorReportsChainedIdentifyingAssociation(): void
    {
        $errors = (new SchemaValidator($this->_em))->validateClass(
            $this->_em->getClassMetadata(GH12417Level2::class),
        );

        self::assertContains(
            "Cannot map association 'Doctrine\Tests\ORM\Functional\Ticket\GH12417Level2#level1 as identifier, because the target entity 'Doctrine\Tests\ORM\Functional\Ticket\GH12417Level1' also maps an association as identifier.",
            $errors,
        );
    }

    public function testPersistingChainedIdentifyingAssociationUsesScalarIdentityMapKeys(): void
    {
        $level0 = new GH12417Level0();
        $level1 = new GH12417Level1($level0);
        $level2 = new GH12417Level2($level1);
        $item   = new GH12417Item(1, $level2);

        $this->_em->persist($level0);
        $this->_em->persist($level1);
        $this->_em->persist($level2);
        $this->_em->persist($item);
        $this->_em->flush();

        $level0Id = $level0->getId();
        self::assertNotNull($level0Id);

        $this->_em->clear();

        $reloadedItem = $this->_em->find(GH12417Item::class, 1);
        self::assertInstanceOf(GH12417Item::class, $reloadedItem);
        self::assertSame($level0Id, $reloadedItem->getLevel2()->getLevel1()->getLevel0()->getId());
    }

    public function testHydratingAssociationToChainedIdentifyingAssociationLazyLoadsNestedIdentifier(): void
    {
        $this->_em->getConnection()->insert('gh12417_level0', ['id' => 101]);
        $this->_em->getConnection()->insert('gh12417_level1', ['level1_id' => 101]);
        $this->_em->getConnection()->insert('gh12417_level2', ['level2_id' => 101]);
        $this->_em->getConnection()->insert('gh12417_item', ['id' => 101, 'level2_id' => 101]);

        $item = $this->_em->find(GH12417Item::class, 101);

        self::assertInstanceOf(GH12417Item::class, $item);
        self::assertSame(101, $item->getLevel2()->getLevel1()->getLevel0()->getId());
    }

    public function testReferenceWithChainedIdentifyingAssociationCanUseEntityIdentifiers(): void
    {
        $this->_em->getConnection()->insert('gh12417_level0', ['id' => 201]);
        $this->_em->getConnection()->insert('gh12417_level1', ['level1_id' => 201]);
        $this->_em->getConnection()->insert('gh12417_level2', ['level2_id' => 201]);

        $level0 = $this->_em->getReference(GH12417Level0::class, 201);
        $level1 = $this->_em->getReference(GH12417Level1::class, ['level0' => $level0]);
        $level2 = $this->_em->getReference(GH12417Level2::class, ['level1' => $level1]);

        self::assertInstanceOf(GH12417Level2::class, $level2);
        self::assertSame(201, $level2->getLevel1()->getLevel0()->getId());
    }

    public function testReferenceWithChainedIdentifyingAssociationCanUseScalarIdentifier(): void
    {
        $this->_em->getConnection()->insert('gh12417_level0', ['id' => 301]);
        $this->_em->getConnection()->insert('gh12417_level1', ['level1_id' => 301]);
        $this->_em->getConnection()->insert('gh12417_level2', ['level2_id' => 301]);

        $level2 = $this->_em->getReference(GH12417Level2::class, 301);

        self::assertInstanceOf(GH12417Level2::class, $level2);
        self::assertSame(301, $level2->getLevel1()->getLevel0()->getId());
    }

    public function testFindWithChainedIdentifyingAssociationCanUseScalarAndEntityIdentifiers(): void
    {
        $this->_em->getConnection()->insert('gh12417_level0', ['id' => 401]);
        $this->_em->getConnection()->insert('gh12417_level1', ['level1_id' => 401]);
        $this->_em->getConnection()->insert('gh12417_level2', ['level2_id' => 401]);

        $level0 = $this->_em->getReference(GH12417Level0::class, 401);
        $level1 = $this->_em->find(GH12417Level1::class, $level0);
        self::assertInstanceOf(GH12417Level1::class, $level1);

        $level2ByEntity = $this->_em->find(GH12417Level2::class, $level1);
        $level2ByScalar = $this->_em->find(GH12417Level2::class, 401);

        self::assertInstanceOf(GH12417Level2::class, $level2ByEntity);
        self::assertSame($level2ByEntity, $level2ByScalar);
        self::assertSame(401, $level2ByEntity->getLevel1()->getLevel0()->getId());
    }

    public function testScalarAndEntityReferencesShareIdentityMapEntry(): void
    {
        $this->_em->getConnection()->insert('gh12417_level0', ['id' => 501]);
        $this->_em->getConnection()->insert('gh12417_level1', ['level1_id' => 501]);
        $this->_em->getConnection()->insert('gh12417_level2', ['level2_id' => 501]);

        $level0         = $this->_em->getReference(GH12417Level0::class, 501);
        $level1ByEntity = $this->_em->getReference(GH12417Level1::class, ['level0' => $level0]);
        $level1ByScalar = $this->_em->getReference(GH12417Level1::class, 501);

        self::assertSame($level1ByEntity, $level1ByScalar);

        $level2ByEntity = $this->_em->getReference(GH12417Level2::class, ['level1' => $level1ByEntity]);
        $level2ByScalar = $this->_em->getReference(GH12417Level2::class, 501);

        self::assertSame($level2ByEntity, $level2ByScalar);
        self::assertSame(501, $level2ByScalar->getLevel1()->getLevel0()->getId());
    }

    public function testQueryParameterCanUseDetachedChainedIdentifyingAssociation(): void
    {
        $this->_em->getConnection()->insert('gh12417_level0', ['id' => 601]);
        $this->_em->getConnection()->insert('gh12417_level1', ['level1_id' => 601]);
        $this->_em->getConnection()->insert('gh12417_level2', ['level2_id' => 601]);
        $this->_em->getConnection()->insert('gh12417_item', ['id' => 601, 'level2_id' => 601]);

        $level0 = $this->_em->getReference(GH12417Level0::class, 601);
        $level2 = new GH12417Level2(new GH12417Level1($level0));

        $items = $this->_em->createQuery('SELECT i FROM ' . GH12417Item::class . ' i WHERE i.level2 = :level2')
            ->setParameter('level2', $level2)
            ->getResult();

        self::assertCount(1, $items);
        self::assertInstanceOf(GH12417Item::class, $items[0]);
        self::assertSame(601, $items[0]->getLevel2()->getLevel1()->getLevel0()->getId());
    }

    public function testCriteriaCanUseDetachedChainedIdentifyingAssociation(): void
    {
        $this->_em->getConnection()->insert('gh12417_level0', ['id' => 651]);
        $this->_em->getConnection()->insert('gh12417_level1', ['level1_id' => 651]);
        $this->_em->getConnection()->insert('gh12417_level2', ['level2_id' => 651]);
        $this->_em->getConnection()->insert('gh12417_item', ['id' => 651, 'level2_id' => 651]);

        $level0 = $this->_em->getReference(GH12417Level0::class, 651);
        $level2 = new GH12417Level2(new GH12417Level1($level0));

        $items = $this->_em->getRepository(GH12417Item::class)->findBy(['level2' => $level2]);

        self::assertCount(1, $items);
        self::assertSame(651, $items[0]->getLevel2()->getLevel1()->getLevel0()->getId());
    }

    public function testInverseCollectionCanLoadFromChainedIdentifyingAssociationOwner(): void
    {
        $this->_em->getConnection()->insert('gh12417_level0', ['id' => 701]);
        $this->_em->getConnection()->insert('gh12417_level1', ['level1_id' => 701]);
        $this->_em->getConnection()->insert('gh12417_level2', ['level2_id' => 701]);
        $this->_em->getConnection()->insert('gh12417_item', ['id' => 701, 'level2_id' => 701]);
        $this->_em->getConnection()->insert('gh12417_item', ['id' => 702, 'level2_id' => 701]);

        $level2 = $this->_em->getReference(GH12417Level2::class, 701);

        self::assertCount(2, $level2->getItems());
    }

    public function testManyToManyCollectionCanLoadFromChainedIdentifyingAssociationOwner(): void
    {
        $this->_em->getConnection()->insert('gh12417_level0', ['id' => 801]);
        $this->_em->getConnection()->insert('gh12417_level1', ['level1_id' => 801]);
        $this->_em->getConnection()->insert('gh12417_level2', ['level2_id' => 801]);
        $this->_em->getConnection()->insert('gh12417_tag', ['id' => 801, 'name' => 'tag']);
        $this->_em->getConnection()->insert('gh12417_level2_tags', ['level2_id' => 801, 'tag_id' => 801]);

        $level2 = $this->_em->getReference(GH12417Level2::class, 801);
        $tags   = $level2->getTags();

        self::assertCount(1, $tags);
        self::assertInstanceOf(GH12417Tag::class, $tags->first());
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12417_level0')]
class GH12417Level0
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    public int|null $id = null;

    public function getId(): int|null
    {
        return $this->id;
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12417_level1')]
class GH12417Level1
{
    public function __construct(
        #[ORM\Id]
        #[ORM\OneToOne(targetEntity: GH12417Level0::class)]
        #[ORM\JoinColumn(name: 'level1_id', referencedColumnName: 'id')]
        public GH12417Level0 $level0,
    ) {
    }

    public function getLevel0(): GH12417Level0
    {
        return $this->level0;
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12417_level2')]
class GH12417Level2
{
    /** @var Collection<int, GH12417Item> */
    #[ORM\OneToMany(targetEntity: GH12417Item::class, mappedBy: 'level2')]
    public Collection $items;

    /** @var Collection<int, GH12417Tag> */
    #[ORM\ManyToMany(targetEntity: GH12417Tag::class)]
    #[ORM\JoinTable(name: 'gh12417_level2_tags')]
    #[ORM\JoinColumn(name: 'level2_id', referencedColumnName: 'level2_id')]
    #[ORM\InverseJoinColumn(name: 'tag_id', referencedColumnName: 'id')]
    public Collection $tags;

    public function __construct(
        #[ORM\Id]
        #[ORM\OneToOne(targetEntity: GH12417Level1::class)]
        #[ORM\JoinColumn(name: 'level2_id', referencedColumnName: 'level1_id')]
        public GH12417Level1 $level1,
    ) {
        $this->items = new ArrayCollection();
        $this->tags  = new ArrayCollection();
    }

    public function getLevel1(): GH12417Level1
    {
        return $this->level1;
    }

    /** @return Collection<int, GH12417Item> */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /** @return Collection<int, GH12417Tag> */
    public function getTags(): Collection
    {
        return $this->tags;
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12417_item')]
class GH12417Item
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\ManyToOne(targetEntity: GH12417Level2::class)]
    #[ORM\JoinColumn(name: 'level2_id', referencedColumnName: 'level2_id')]
    public GH12417Level2 $level2;

    public function __construct(int $id, GH12417Level2 $level2)
    {
        $this->id     = $id;
        $this->level2 = $level2;
    }

    public function getLevel2(): GH12417Level2
    {
        return $this->level2;
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh12417_tag')]
class GH12417Tag
{
    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'integer')]
        public int $id,
        #[ORM\Column(type: 'string')]
        public string $name,
    ) {
    }
}
