<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Doctrine\Tests\OrmFunctionalTestCase;
use SortDirection;

use function array_keys;

final class GH12530Test extends OrmFunctionalTestCase
{
    private const FILTER_NAME = 'gh_12530_filter';

    protected function setUp(): void
    {
        parent::setUp();

        $this->_em->getConfiguration()->addFilter(self::FILTER_NAME, GH12530Filter::class);

        $this->createSchemaForModels(
            GH12530Node::class,
            GH12530AbstractButton::class,
            GH12530ChildButton::class,
            GH12530OtherChildButton::class,
            GH12530FilteredEntity::class,
            GH12530IndexedNode::class,
            GH12530AbstractIndexedItem::class,
            GH12530IndexedItem::class,
            GH12530IndexedItemGroup::class,
        );
    }

    public function testIndexedEagerCollectionSurvivesUnrelatedFilterChanges(): void
    {
        $otherRoot = new GH12530OtherChildButton('other-root', 0);
        $otherRoot->addChild(new GH12530OtherChildButton('other-child', 0));

        $childRoot = new GH12530ChildButton('child-root', 0);
        $childRoot->addChild(new GH12530ChildButton('child-a', 0));
        $childRoot->addChild(new GH12530ChildButton('child-b', 1));

        $activeFilteredEntity  = new GH12530FilteredEntity(null);
        $deletedFilteredEntity = new GH12530FilteredEntity(new DateTimeImmutable('2026-01-01'));

        $this->_em->persist($otherRoot);
        $this->_em->persist($childRoot);
        $this->_em->persist($activeFilteredEntity);
        $this->_em->persist($deletedFilteredEntity);
        $this->_em->flush();

        self::assertGreaterThan(0, $otherRoot->getId());
        self::assertGreaterThan(0, $childRoot->getId());
        self::assertGreaterThan(0, $activeFilteredEntity->getId());
        self::assertGreaterThan(0, $deletedFilteredEntity->getId());

        $this->_em->clear();

        $otherButtons = $this->_em->getRepository(GH12530OtherChildButton::class)->findBy(['parent' => null]);

        self::assertCount(1, $otherButtons);
        self::assertSame('other-root', $otherButtons[0]->getText());
        self::assertSame([0], $otherButtons[0]->getChildren()->getKeys());
        self::assertSame('other-child', $otherButtons[0]->getChildren()->get(0)?->getText());
        self::assertSame($otherButtons[0], $otherButtons[0]->getChildren()->get(0)->getParent());

        $this->_em->getFilters()->enable(self::FILTER_NAME);

        $filteredEntities = $this->_em->getRepository(GH12530FilteredEntity::class)->findAll();

        self::assertCount(1, $filteredEntities);
        self::assertNull($filteredEntities[0]->getDeletedAt());

        $childButtons = $this->_em->getRepository(GH12530ChildButton::class)->findBy(['parent' => null]);

        self::assertCount(1, $childButtons);
        self::assertSame('child-root', $childButtons[0]->getText());
        self::assertSame([0, 1], $childButtons[0]->getChildren()->getKeys());
        self::assertSame('child-a', $childButtons[0]->getChildren()->get(0)?->getText());
        self::assertSame('child-b', $childButtons[0]->getChildren()->get(1)?->getText());
        self::assertSame($childButtons[0], $childButtons[0]->getChildren()->get(0)->getParent());
        self::assertSame($childButtons[0], $childButtons[0]->getChildren()->get(1)?->getParent());

        $this->_em->clear();
        $this->_em->getFilters()->disable(self::FILTER_NAME);

        $childButtons = $this->_em->getRepository(GH12530ChildButton::class)->findBy(['parent' => null]);

        self::assertCount(1, $childButtons);
        self::assertSame([0, 1], $childButtons[0]->getChildren()->getKeys());
    }

    public function testLimitedIndexedCollectionSurvivesUnrelatedFilterChanges(): void
    {
        $group  = new GH12530IndexedItemGroup();
        $first  = new GH12530IndexedItem('first', 0);
        $second = new GH12530IndexedItem('second', 1);
        $group->addItem($first);
        $group->addItem($second);

        $this->_em->persist($group);
        $this->_em->flush();

        self::assertGreaterThan(0, $first->getId());
        self::assertGreaterThan(0, $second->getId());

        $this->_em->clear();

        $group = $this->_em->find(GH12530IndexedItemGroup::class, $group->getId());
        self::assertInstanceOf(GH12530IndexedItemGroup::class, $group);

        $items = $group->getItems()->slice(0, 2);

        self::assertSame([0, 1], array_keys($items));
        self::assertSame(['first', 'second'], [$items[0]->getLabel(), $items[1]->getLabel()]);

        $groupId = $group->getId();
        $this->_em->clear();
        $this->_em->getFilters()->enable(self::FILTER_NAME);

        $group = $this->_em->find(GH12530IndexedItemGroup::class, $groupId);
        self::assertInstanceOf(GH12530IndexedItemGroup::class, $group);

        $items = $group->getItems()->slice(0, 2);

        self::assertSame([0, 1], array_keys($items));
        self::assertSame(['first', 'second'], [$items[0]->getLabel(), $items[1]->getLabel()]);
    }
}

final class GH12530Filter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, string $targetTableAlias): string
    {
        if ($targetEntity->name !== GH12530FilteredEntity::class) {
            return '';
        }

        return $targetTableAlias . '.deleted_at IS NULL';
    }
}

#[ORM\Entity]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'dtype', type: 'string')]
#[ORM\DiscriminatorMap([
    'child' => GH12530ChildButton::class,
    'other' => GH12530OtherChildButton::class,
])]
abstract class GH12530Node
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id = 0;

    #[ORM\Column(type: 'integer')]
    protected int $sortIndex;

    public function getId(): int
    {
        return $this->id;
    }

    public function getSortIndex(): int
    {
        return $this->sortIndex;
    }
}

#[ORM\Entity]
abstract class GH12530AbstractButton extends GH12530Node
{
    #[ORM\Column(type: 'string')]
    private string $text;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private GH12530AbstractButton|null $parent = null;

    /** @var Collection<int, GH12530AbstractButton> */
    #[ORM\OneToMany(
        targetEntity: self::class,
        mappedBy: 'parent',
        indexBy: 'sortIndex',
        cascade: ['persist'],
        fetch: 'EAGER',
    )]
    #[ORM\OrderBy(['sortIndex' => SortDirection::Ascending])]
    private Collection $children;

    public function __construct(string $text, int $sortIndex)
    {
        $this->text      = $text;
        $this->sortIndex = $sortIndex;
        $this->children  = new ArrayCollection();
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getParent(): GH12530AbstractButton|null
    {
        return $this->parent;
    }

    /** @return Collection<int, GH12530AbstractButton> */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(GH12530AbstractButton $child): void
    {
        $child->parent = $this;

        $this->children->set($child->getSortIndex(), $child);
    }
}

#[ORM\Entity]
final class GH12530ChildButton extends GH12530AbstractButton
{
}

#[ORM\Entity]
final class GH12530OtherChildButton extends GH12530AbstractButton
{
}

#[ORM\Entity]
final class GH12530FilteredEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id = 0;

    #[ORM\Column(name: 'deleted_at', type: 'datetime_immutable', nullable: true)]
    private DateTimeImmutable|null $deletedAt;

    public function __construct(DateTimeImmutable|null $deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDeletedAt(): DateTimeImmutable|null
    {
        return $this->deletedAt;
    }
}

#[ORM\Entity]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'dtype', type: 'string')]
#[ORM\DiscriminatorMap(['item' => GH12530IndexedItem::class])]
abstract class GH12530IndexedNode
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id = 0;

    #[ORM\Column(type: 'integer')]
    protected int $sortIndex;

    public function getId(): int
    {
        return $this->id;
    }

    public function getSortIndex(): int
    {
        return $this->sortIndex;
    }
}

#[ORM\Entity]
abstract class GH12530AbstractIndexedItem extends GH12530IndexedNode
{
    #[ORM\Column(type: 'string')]
    private string $label;

    public function __construct(string $label, int $sortIndex)
    {
        $this->label     = $label;
        $this->sortIndex = $sortIndex;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}

#[ORM\Entity]
final class GH12530IndexedItem extends GH12530AbstractIndexedItem
{
}

#[ORM\Entity]
final class GH12530IndexedItemGroup
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id = 0;

    /** @var Collection<int, GH12530AbstractIndexedItem> */
    #[ORM\ManyToMany(
        targetEntity: GH12530AbstractIndexedItem::class,
        indexBy: 'sortIndex',
        cascade: ['persist'],
        fetch: 'EXTRA_LAZY',
    )]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /** @return Collection<int, GH12530AbstractIndexedItem> */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(GH12530AbstractIndexedItem $item): void
    {
        $this->items->set($item->getSortIndex(), $item);
    }
}
