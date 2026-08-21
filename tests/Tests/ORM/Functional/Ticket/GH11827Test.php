<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Tests\Models\CMS\CmsGroup;
use Doctrine\Tests\Models\CMS\CmsUser;
use Doctrine\Tests\OrmFunctionalTestCase;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Attributes\Group;

use function array_filter;
use function array_values;
use function assert;
use function str_contains;
use function str_starts_with;

#[Group('GH-11827')]
class GH11827Test extends OrmFunctionalTestCase
{
    protected function setUp(): void
    {
        $this->useModelSet('cms');

        parent::setUp();

        $this->setUpEntitySchema([
            GH11827OrphanRemovalComment::class,
            GH11827OrphanRemovalPost::class,
        ]);
    }

    protected function tearDown(): void
    {
        if (static::$sharedConn !== null) {
            static::$sharedConn->executeStatement('DELETE FROM gh11827_orphan_post_comments');
            static::$sharedConn->executeStatement('DELETE FROM gh11827_orphan_comments');
            static::$sharedConn->executeStatement('DELETE FROM gh11827_orphan_posts');
        }

        parent::tearDown();
    }

    public function testNestedFlushInPostFlushDoesNotRepeatClearedCollectionDeletion(): void
    {
        $user   = $this->createUserWithGroups(2);
        $userId = $user->getId();

        $this->addNestedFlushPostFlushListener();
        $this->clearAndReAddGroups($user);

        $this->_em->flush();
        $this->_em->clear();

        $freshUser = $this->_em->find(CmsUser::class, $userId);
        assert($freshUser instanceof CmsUser);

        self::assertCount(2, $freshUser->groups);
    }

    public function testCollectionQueuesRemainVisibleOnFlushAndAreClearedBeforePostFlush(): void
    {
        $user     = $this->createUserWithGroups(2);
        $listener = new class {
            public bool $onFlushCalled = false;

            public bool $postFlushCalled = false;

            public function onFlush(OnFlushEventArgs $args): void
            {
                $this->onFlushCalled = true;

                $uow = $args->getObjectManager()->getUnitOfWork();

                Assert::assertCount(1, $uow->getScheduledCollectionDeletions());
                Assert::assertCount(1, $uow->getScheduledCollectionUpdates());
            }

            public function postFlush(): void
            {
                $this->postFlushCalled = true;
            }
        };

        $this->_em->getEventManager()->addEventListener(Events::onFlush, $listener);
        $this->_em->getEventManager()->addEventListener(Events::postFlush, $listener);

        $this->clearAndReAddGroups($user);

        $this->_em->flush();

        self::assertTrue($listener->onFlushCalled);
        self::assertTrue($listener->postFlushCalled);
        self::assertSame([], $this->_em->getUnitOfWork()->getScheduledCollectionDeletions());
        self::assertSame([], $this->_em->getUnitOfWork()->getScheduledCollectionUpdates());
    }

    public function testNestedFlushInPostFlushDoesNotRepeatDereferencedCollectionDeletion(): void
    {
        $user      = $this->createUserWithGroups(2);
        $userId    = $user->getId();
        $newGroupA = $this->createGroup('New group A');
        $newGroupB = $this->createGroup('New group B');

        $this->_em->persist($newGroupA);
        $this->_em->persist($newGroupB);
        $this->_em->flush();

        $this->addNestedFlushPostFlushListener();

        $user->groups = new ArrayCollection([$newGroupA, $newGroupB]);

        $this->_em->flush();
        $this->_em->clear();

        $freshUser = $this->_em->find(CmsUser::class, $userId);
        assert($freshUser instanceof CmsUser);

        self::assertCount(2, $freshUser->groups);
        self::assertSame('New group A', $freshUser->groups[0]->name);
        self::assertSame('New group B', $freshUser->groups[1]->name);
    }

    public function testNestedFlushInPostFlushKeepsPendingCollectionElementRemovalsApplied(): void
    {
        $user          = $this->createUserWithGroups(2);
        $userId        = $user->getId();
        $removedGroup  = $user->groups[0];
        $remainingName = $user->groups[1]->name;

        assert($removedGroup instanceof CmsGroup);

        $this->addNestedFlushPostFlushListener();

        $this->_em->remove($removedGroup);
        $this->_em->flush();

        self::assertCount(1, $user->groups);
        self::assertSame($remainingName, $user->groups[1]->name);

        $this->_em->clear();

        $freshUser = $this->_em->find(CmsUser::class, $userId);
        assert($freshUser instanceof CmsUser);

        self::assertCount(1, $freshUser->groups);
        self::assertSame($remainingName, $freshUser->groups[0]->name);
    }

    public function testNestedFlushInPostFlushExecutesJoinTableCollectionDeleteOnlyOnce(): void
    {
        $user = $this->createUserWithGroups(2);

        $this->addNestedFlushPostFlushListener(false);
        $this->clearAndReAddGroups($user);

        $queryLog = $this->getQueryLog();
        $queryLog->reset()->enable();

        $this->_em->flush();

        $joinTableDeletes = array_values(array_filter($queryLog->queries, static function (array $entry): bool {
            return str_starts_with($entry['sql'], 'DELETE')
                && str_contains($entry['sql'], 'cms_users_groups');
        }));

        self::assertCount(1, $joinTableDeletes);
    }

    public function testNestedFlushInPostFlushDoesNotRepeatClearDeletionForOrphanRemovalCollection(): void
    {
        $post             = new GH11827OrphanRemovalPost();
        $commentA         = new GH11827OrphanRemovalComment();
        $commentB         = new GH11827OrphanRemovalComment();
        $post->comments[] = $commentA;
        $post->comments[] = $commentB;

        $this->_em->persist($post);
        $this->_em->flush();

        $postId = $post->id;

        $this->addNestedFlushPostFlushListener();

        $post->comments->clear();
        $post->comments->add($commentA);

        $this->_em->flush();
        $this->_em->clear();

        $freshPost = $this->_em->find(GH11827OrphanRemovalPost::class, $postId);
        assert($freshPost instanceof GH11827OrphanRemovalPost);

        self::assertCount(1, $freshPost->comments);
        self::assertCount(1, $this->_em->getRepository(GH11827OrphanRemovalComment::class)->findAll());
    }

    private function createUserWithGroups(int $groupCount): CmsUser
    {
        $user           = new CmsUser();
        $user->name     = 'Guilherme';
        $user->username = 'gblanco';
        $user->status   = 'developer';

        for ($i = 0; $i < $groupCount; ++$i) {
            $user->addGroup($this->createGroup('Developers ' . $i));
        }

        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    private function createGroup(string $name): CmsGroup
    {
        $group       = new CmsGroup();
        $group->name = $name;

        return $group;
    }

    private function clearAndReAddGroups(CmsUser $user): void
    {
        $groups = $user->groups->toArray();

        $user->groups->clear();

        foreach ($groups as $group) {
            $user->groups[] = $group;
        }
    }

    private function addNestedFlushPostFlushListener(bool $assertCollectionQueuesAreEmpty = true): void
    {
        $this->_em->getEventManager()->addEventListener(Events::postFlush, new class ($this->_em, $assertCollectionQueuesAreEmpty) {
            private bool $nestedFlushDone = false;

            public function __construct(
                private readonly EntityManagerInterface $em,
                private readonly bool $assertCollectionQueuesAreEmpty,
            ) {
            }

            public function postFlush(): void
            {
                if ($this->nestedFlushDone) {
                    return;
                }

                $this->nestedFlushDone = true;

                if ($this->assertCollectionQueuesAreEmpty) {
                    Assert::assertSame([], $this->em->getUnitOfWork()->getScheduledCollectionDeletions());
                    Assert::assertSame([], $this->em->getUnitOfWork()->getScheduledCollectionUpdates());
                }

                $this->em->flush();
            }
        });
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh11827_orphan_posts')]
class GH11827OrphanRemovalPost
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    public int|null $id = null;

    /** @phpstan-var Collection<int, GH11827OrphanRemovalComment> */
    #[ORM\JoinTable(name: 'gh11827_orphan_post_comments')]
    #[ORM\ManyToMany(targetEntity: GH11827OrphanRemovalComment::class, orphanRemoval: true, cascade: ['persist'])]
    public Collection $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }
}

#[ORM\Entity]
#[ORM\Table(name: 'gh11827_orphan_comments')]
class GH11827OrphanRemovalComment
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    public int|null $id = null;
}
