<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Exception\FlushDuringCommit;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Tests\Models\CMS\CmsGroup;
use Doctrine\Tests\Models\CMS\CmsUser;
use Doctrine\Tests\OrmFunctionalTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use RuntimeException;

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
            GH11827CascadeChild::class,
            GH11827CascadeParent::class,
            GH11827OrphanRemovalComment::class,
            GH11827OrphanRemovalPost::class,
            GH11827PreFlushCallbackEntity::class,
        ]);
    }

    protected function tearDown(): void
    {
        if (static::$sharedConn !== null) {
            static::$sharedConn->executeStatement('DELETE FROM gh11827_cascade_parents');
            static::$sharedConn->executeStatement('DELETE FROM gh11827_cascade_children');
            static::$sharedConn->executeStatement('DELETE FROM gh11827_orphan_post_comments');
            static::$sharedConn->executeStatement('DELETE FROM gh11827_orphan_comments');
            static::$sharedConn->executeStatement('DELETE FROM gh11827_orphan_posts');
            static::$sharedConn->executeStatement('DELETE FROM gh11827_pre_flush_callback_entities');
        }

        parent::tearDown();
    }

    /** @phpstan-return array<string, array{string}> */
    public static function reentrantFlushEvents(): array
    {
        return [
            'preFlush'  => [Events::preFlush],
            'onFlush'  => [Events::onFlush],
            'postFlush' => [Events::postFlush],
        ];
    }

    /** @phpstan-return array<string, array{string, string}> */
    public static function reentrantFlushEntityEvents(): array
    {
        return [
            'postPersist' => [Events::postPersist, 'insert'],
            'preUpdate'   => [Events::preUpdate, 'update'],
            'postUpdate'  => [Events::postUpdate, 'update'],
            'postRemove'  => [Events::postRemove, 'remove'],
        ];
    }

    #[DataProvider('reentrantFlushEvents')]
    public function testFlushFromFlushEventListenerIsRejected(string $eventName): void
    {
        $this->_em->getEventManager()->addEventListener($eventName, new class ($this->_em) {
            public function __construct(private readonly EntityManagerInterface $em)
            {
            }

            public function preFlush(): void
            {
                $this->em->flush();
            }

            public function onFlush(): void
            {
                $this->em->flush();
            }

            public function postFlush(): void
            {
                $this->em->flush();
            }
        });

        $this->_em->persist($this->createGroup('Developers'));

        $this->expectException(FlushDuringCommit::class);
        $this->_em->flush();
    }

    #[DataProvider('reentrantFlushEntityEvents')]
    public function testFlushFromEntityLifecycleEventListenerIsRejected(string $eventName, string $operation): void
    {
        $this->_em->getEventManager()->addEventListener($eventName, new class ($this->_em) {
            public function __construct(private readonly EntityManagerInterface $em)
            {
            }

            public function prePersist(): void
            {
                $this->em->flush();
            }

            public function postPersist(): void
            {
                $this->em->flush();
            }

            public function preUpdate(): void
            {
                $this->em->flush();
            }

            public function postUpdate(): void
            {
                $this->em->flush();
            }

            public function postRemove(): void
            {
                $this->em->flush();
            }
        });

        $group = $this->createGroup('Developers');

        if ($operation !== 'insert') {
            $this->_em->persist($group);
            $this->_em->flush();
        }

        if ($operation === 'insert') {
            $this->_em->persist($group);
        } elseif ($operation === 'update') {
            $group->name = 'Core Developers';
        } else {
            $this->_em->remove($group);
        }

        $this->expectException(FlushDuringCommit::class);
        $this->_em->flush();
    }

    public function testFlushFromCascadePrePersistListenerDuringCommitIsRejected(): void
    {
        $parent = new GH11827CascadeParent();

        $this->_em->persist($parent);
        $this->_em->flush();

        $this->_em->getEventManager()->addEventListener(Events::prePersist, new class ($this->_em) {
            public function __construct(private readonly EntityManagerInterface $em)
            {
            }

            public function prePersist(): void
            {
                $this->em->flush();
            }
        });

        $parent->child = new GH11827CascadeChild();

        $this->expectException(FlushDuringCommit::class);
        $this->_em->flush();
    }

    public function testFlushFromOrphanRemovalPreRemoveListenerDuringCommitIsRejected(): void
    {
        $post    = new GH11827OrphanRemovalPost();
        $comment = new GH11827OrphanRemovalComment();

        $post->comments[] = $comment;

        $this->_em->persist($post);
        $this->_em->flush();

        $this->_em->getEventManager()->addEventListener(Events::preRemove, new class ($this->_em) {
            public function __construct(private readonly EntityManagerInterface $em)
            {
            }

            public function preRemove(): void
            {
                $this->em->flush();
            }
        });

        $post->comments->clear();

        $this->expectException(FlushDuringCommit::class);
        $this->_em->flush();
    }

    public function testFlushFromEntityPreFlushCallbackDuringCommitIsRejected(): void
    {
        $entity = new GH11827PreFlushCallbackEntity();

        $this->_em->persist($entity);

        $this->expectException(FlushDuringCommit::class);
        $this->_em->flush();
    }

    public function testFlushFromPostFlushListenerIsRejectedWhenNothingIsScheduled(): void
    {
        $listener = new class ($this->_em) {
            public bool $nestedFlushRejected = false;

            private bool $nestedFlushDone = false;

            public function __construct(private readonly EntityManagerInterface $em)
            {
            }

            public function postFlush(): void
            {
                if ($this->nestedFlushDone) {
                    return;
                }

                $this->nestedFlushDone = true;

                try {
                    $this->em->flush();
                } catch (FlushDuringCommit) {
                    $this->nestedFlushRejected = true;
                }
            }
        };

        $this->_em->getEventManager()->addEventListener(Events::postFlush, $listener);

        $this->_em->flush();
        $this->_em->flush();

        self::assertTrue($listener->nestedFlushRejected);
    }

    public function testRejectedPostFlushFlushDoesNotRepeatClearedCollectionDeletionOnNextFlush(): void
    {
        $user   = $this->createUserWithGroups(2);
        $userId = $user->getId();

        $this->addNestedFlushPostFlushListener();
        $this->clearAndReAddGroups($user);

        $queryLog = $this->getQueryLog();
        $queryLog->reset()->enable();

        $this->flushAndCatchReentrantFlush();

        $joinTableDeletes = array_values(array_filter($queryLog->queries, static function (array $entry): bool {
            return str_starts_with($entry['sql'], 'DELETE')
                && str_contains($entry['sql'], 'cms_users_groups');
        }));

        self::assertCount(1, $joinTableDeletes);
        self::assertSame([], $this->_em->getUnitOfWork()->getScheduledCollectionDeletions());
        self::assertSame([], $this->_em->getUnitOfWork()->getScheduledCollectionUpdates());

        $this->_em->flush();
        $this->_em->clear();

        $freshUser = $this->_em->find(CmsUser::class, $userId);
        assert($freshUser instanceof CmsUser);

        self::assertCount(2, $freshUser->groups);
    }

    public function testPostFlushExceptionCleansCompletedCollectionOperations(): void
    {
        $user   = $this->createUserWithGroups(2);
        $userId = $user->getId();

        $listener = new class {
            private bool $exceptionThrown = false;

            public function postFlush(): void
            {
                if ($this->exceptionThrown) {
                    return;
                }

                $this->exceptionThrown = true;

                throw new RuntimeException('Unexpected postFlush failure.');
            }
        };

        $this->_em->getEventManager()->addEventListener(Events::postFlush, $listener);
        $this->clearAndReAddGroups($user);

        $queryLog = $this->getQueryLog();
        $queryLog->reset()->enable();

        $exceptionPropagated = false;

        try {
            $this->_em->flush();
        } catch (RuntimeException $exception) {
            $exceptionPropagated = true;

            self::assertSame('Unexpected postFlush failure.', $exception->getMessage());
        }

        self::assertTrue($exceptionPropagated);

        $joinTableDeletes = array_values(array_filter($queryLog->queries, static function (array $entry): bool {
            return str_starts_with($entry['sql'], 'DELETE')
                && str_contains($entry['sql'], 'cms_users_groups');
        }));

        self::assertCount(1, $joinTableDeletes);
        self::assertSame([], $this->_em->getUnitOfWork()->getScheduledCollectionDeletions());
        self::assertSame([], $this->_em->getUnitOfWork()->getScheduledCollectionUpdates());

        $this->_em->flush();
        $this->_em->clear();

        $freshUser = $this->_em->find(CmsUser::class, $userId);
        assert($freshUser instanceof CmsUser);

        self::assertCount(2, $freshUser->groups);
    }

    public function testCaughtPostFlushFlushRejectionAllowsOuterFlushToComplete(): void
    {
        $user   = $this->createUserWithGroups(2);
        $userId = $user->getId();

        $listener = new class ($this->_em) {
            public bool $nestedFlushRejected = false;

            private bool $nestedFlushDone = false;

            public function __construct(private readonly EntityManagerInterface $em)
            {
            }

            public function postFlush(): void
            {
                if ($this->nestedFlushDone) {
                    return;
                }

                $this->nestedFlushDone = true;

                try {
                    $this->em->flush();
                } catch (FlushDuringCommit) {
                    $this->nestedFlushRejected = true;
                }
            }
        };

        $this->_em->getEventManager()->addEventListener(Events::postFlush, $listener);
        $this->clearAndReAddGroups($user);

        $queryLog = $this->getQueryLog();
        $queryLog->reset()->enable();

        $this->_em->flush();

        $joinTableDeletes = array_values(array_filter($queryLog->queries, static function (array $entry): bool {
            return str_starts_with($entry['sql'], 'DELETE')
                && str_contains($entry['sql'], 'cms_users_groups');
        }));

        self::assertTrue($listener->nestedFlushRejected);
        self::assertCount(1, $joinTableDeletes);

        $this->_em->clear();

        $freshUser = $this->_em->find(CmsUser::class, $userId);
        assert($freshUser instanceof CmsUser);

        self::assertCount(2, $freshUser->groups);
    }

    public function testRejectedPostFlushFlushDoesNotRepeatDereferencedCollectionDeletionOnNextFlush(): void
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

        $this->flushAndCatchReentrantFlush();
        $this->_em->flush();
        $this->_em->clear();

        $freshUser = $this->_em->find(CmsUser::class, $userId);
        assert($freshUser instanceof CmsUser);

        self::assertCount(2, $freshUser->groups);
        self::assertSame('New group A', $freshUser->groups[0]->name);
        self::assertSame('New group B', $freshUser->groups[1]->name);
    }

    public function testRejectedPostFlushFlushDoesNotLeavePendingCollectionElementRemovalsOnNextFlush(): void
    {
        $user          = $this->createUserWithGroups(2);
        $userId        = $user->getId();
        $removedGroup  = $user->groups[0];
        $remainingName = $user->groups[1]->name;

        assert($removedGroup instanceof CmsGroup);

        $this->addNestedFlushPostFlushListener();

        $this->_em->remove($removedGroup);

        $this->flushAndCatchReentrantFlush();

        self::assertCount(1, $user->groups);
        self::assertSame($remainingName, $user->groups[1]->name);

        $this->_em->flush();
        $this->_em->clear();

        $freshUser = $this->_em->find(CmsUser::class, $userId);
        assert($freshUser instanceof CmsUser);

        self::assertCount(1, $freshUser->groups);
        self::assertSame($remainingName, $freshUser->groups[0]->name);
    }

    public function testRejectedPostFlushFlushDoesNotLeaveOrphanRemovalsOnNextFlush(): void
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

        $this->flushAndCatchReentrantFlush();
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

    private function flushAndCatchReentrantFlush(): void
    {
        try {
            $this->_em->flush();
        } catch (FlushDuringCommit) {
            return;
        }

        self::fail('Expected reentrant flush to be rejected.');
    }

    private function addNestedFlushPostFlushListener(): void
    {
        $this->_em->getEventManager()->addEventListener(Events::postFlush, new class ($this->_em) {
            private bool $nestedFlushDone = false;

            public function __construct(private readonly EntityManagerInterface $em)
            {
            }

            public function postFlush(): void
            {
                if ($this->nestedFlushDone) {
                    return;
                }

                $this->nestedFlushDone = true;

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

#[ORM\Entity]
#[ORM\Table(name: 'gh11827_cascade_parents')]
class GH11827CascadeParent
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    public int|null $id = null;

    #[ORM\OneToOne(targetEntity: GH11827CascadeChild::class, cascade: ['persist'])]
    public GH11827CascadeChild|null $child = null;
}

#[ORM\Entity]
#[ORM\Table(name: 'gh11827_cascade_children')]
class GH11827CascadeChild
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    public int|null $id = null;
}

#[ORM\Entity]
#[ORM\Table(name: 'gh11827_pre_flush_callback_entities')]
#[ORM\HasLifecycleCallbacks]
class GH11827PreFlushCallbackEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    public int|null $id = null;

    #[ORM\Column(type: 'string')]
    public string $name = 'Developers';

    #[ORM\PreFlush]
    public function preFlush(PreFlushEventArgs $event): void
    {
        $event->getObjectManager()->flush();
    }
}
