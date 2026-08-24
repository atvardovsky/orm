<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket\GH12077;

use Doctrine\ORM\UnitOfWork;
use Doctrine\Tests\OrmFunctionalTestCase;
use ReflectionProperty;

use function method_exists;

final class GH12077Test extends OrmFunctionalTestCase
{
    public function testCanRemoveEntityWithHookedGeneratedIdentifier(): void
    {
        $this->skipIfPropertyHooksAreUnavailable();

        $this->createSchemaForModels(HookedGeneratedIdentifierEntity::class);

        $entity = new HookedGeneratedIdentifierEntity();

        $this->_em->persist($entity);
        $this->_em->flush();

        $id = $entity->id;

        $this->_em->remove($entity);
        $this->_em->flush();

        self::assertSame($id, $entity->id);
        self::assertFalse($this->_em->contains($entity));
        self::assertSame(UnitOfWork::STATE_NEW, $this->_em->getUnitOfWork()->getEntityState($entity));
        self::assertNull($this->_em->find(HookedGeneratedIdentifierEntity::class, $id));
    }

    public function testCanRemoveEntityWithHookedPostInsertGeneratedIdentifier(): void
    {
        $this->skipIfPropertyHooksAreUnavailable();

        $this->createSchemaForModels(HookedPostInsertGeneratedIdentifierEntity::class);

        $entity = new HookedPostInsertGeneratedIdentifierEntity();

        $this->_em->persist($entity);
        $this->_em->flush();

        $id = $entity->id;

        $this->_em->remove($entity);
        $this->_em->flush();

        self::assertSame($id, $entity->id);
        self::assertFalse($this->_em->contains($entity));
        self::assertSame(['id' => $id], $this->_em->getClassMetadata($entity::class)->getIdentifierValues($entity));
        self::assertSame(UnitOfWork::STATE_DETACHED, $this->_em->getUnitOfWork()->getEntityState($entity));
        self::assertNull($this->_em->find(HookedPostInsertGeneratedIdentifierEntity::class, $id));
    }

    public function testGeneratedIdentifierWithoutHooksIsStillClearedAfterRemoval(): void
    {
        $this->createSchemaForModels(PlainGeneratedIdentifierEntity::class);

        $entity = new PlainGeneratedIdentifierEntity();

        $this->_em->persist($entity);
        $this->_em->flush();

        $id = $entity->id;

        $this->_em->remove($entity);
        $this->_em->flush();

        self::assertFalse($this->_em->contains($entity));
        self::assertSame([], $this->_em->getClassMetadata($entity::class)->getIdentifierValues($entity));
        self::assertSame(UnitOfWork::STATE_NEW, $this->_em->getUnitOfWork()->getEntityState($entity));
        self::assertNull($this->_em->find(PlainGeneratedIdentifierEntity::class, $id));
    }

    public function testAssignedIdentifierIsNotClearedAfterRemoval(): void
    {
        $this->createSchemaForModels(AssignedIdentifierEntity::class);

        $entity     = new AssignedIdentifierEntity();
        $entity->id = 12077;

        $this->_em->persist($entity);
        $this->_em->flush();

        $this->_em->remove($entity);
        $this->_em->flush();

        self::assertSame(12077, $entity->id);
        self::assertFalse($this->_em->contains($entity));
        self::assertSame(['id' => 12077], $this->_em->getClassMetadata($entity::class)->getIdentifierValues($entity));
        self::assertSame(UnitOfWork::STATE_NEW, $this->_em->getUnitOfWork()->getEntityState($entity));
        self::assertNull($this->_em->find(AssignedIdentifierEntity::class, 12077));
    }

    private function skipIfPropertyHooksAreUnavailable(): void
    {
        if (! method_exists(ReflectionProperty::class, 'getHooks')) {
            self::markTestSkipped('ReflectionProperty hook metadata is required.');
        }

        if (! $this->_em->getConfiguration()->isNativeLazyObjectsEnabled()) {
            self::markTestSkipped('Property hooks require native lazy objects to be enabled.');
        }
    }
}
