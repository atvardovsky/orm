<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Result as DriverResult;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\OraclePlatform;
use Doctrine\DBAL\Portability\Converter;
use Doctrine\DBAL\Portability\Result as PortabilityResult;
use Doctrine\DBAL\Result as DbalResult;
use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
use Doctrine\ORM\Internal\Hydration\ArrayHydrator;
use Doctrine\ORM\Internal\Hydration\ObjectHydrator;
use Doctrine\ORM\Internal\Hydration\ScalarHydrator;
use Doctrine\ORM\Internal\Hydration\SimpleObjectHydrator;
use Doctrine\ORM\Internal\Hydration\SingleScalarHydrator;
use Doctrine\ORM\Persisters\Entity\BasicEntityPersister;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Tests\Mocks\ArrayResultFactory;
use Doctrine\Tests\Mocks\AttributeDriverFactory;
use Doctrine\Tests\Mocks\EntityManagerMock;
use Doctrine\Tests\Models\CMS\CmsPhonenumber;
use Doctrine\Tests\Models\CMS\CmsUser;
use Doctrine\Tests\Models\CMS\CmsUserDTO;
use Doctrine\Tests\Models\Company\CompanyPerson;
use Doctrine\Tests\Models\Enums\Suit;
use Doctrine\Tests\OrmTestCase;
use PHPUnit\Framework\Attributes\Group;
use ReflectionClass;

use function array_keys;
use function class_exists;
use function iterator_to_array;

final class GH12272Test extends OrmTestCase
{
    #[Group('GH-12272')]
    public function testGeneratedScalarHydrationUsesActualDbalResultColumnNames(): void
    {
        $this->requireDbalPortabilityResultColumnNames();

        $entityManager = $this->createCmsEntityManagerWithPlatform(new OraclePlatform());
        $rsm           = $this->createGeneratedCmsUserResultSetMapping($entityManager);
        $stmt          = $this->createPortableLowerCaseResult($this->createUpperCaseCmsUserRows(), $entityManager->getConnection());
        $hydrator      = new ScalarHydrator($entityManager);

        self::assertSame('id0', $stmt->getColumnName(0));

        self::assertSame(
            [
                [
                    'u_id' => 1,
                    'u_status' => 'active',
                    'u_username' => 'romanb',
                    'u_name' => 'Roman',
                    'u_email_id' => null,
                ],
            ],
            $hydrator->hydrateAll($stmt, $rsm),
        );
    }

    #[Group('GH-12272')]
    public function testGeneratedScalarIterableUsesActualDbalResultColumnNames(): void
    {
        $this->requireDbalPortabilityResultColumnNames();

        $entityManager = $this->createCmsEntityManagerWithPlatform(new OraclePlatform());
        $rsm           = $this->createGeneratedCmsUserResultSetMapping($entityManager);
        $stmt          = $this->createPortableLowerCaseResult($this->createUpperCaseCmsUserRows(), $entityManager->getConnection());
        $hydrator      = new ScalarHydrator($entityManager);

        self::assertSame(
            [
                [
                    'u_id' => 1,
                    'u_status' => 'active',
                    'u_username' => 'romanb',
                    'u_name' => 'Roman',
                    'u_email_id' => null,
                ],
            ],
            iterator_to_array($hydrator->toIterable($stmt, $rsm), false),
        );
    }

    #[Group('GH-12272')]
    public function testDqlQueryMarksGeneratedResultSetMappingBeforeHydration(): void
    {
        GH12272GeneratedMappingCapturingHydrator::reset();

        $entityManager = $this->createCmsEntityManagerWithDriverResult(
            new OraclePlatform(),
            ArrayResultFactory::createDriverResultFromArray([['ID0' => '1']]),
        );
        $entityManager->getConfiguration()->addCustomHydrationMode(
            GH12272GeneratedMappingCapturingHydrator::class,
            GH12272GeneratedMappingCapturingHydrator::class,
        );

        $entityManager->createQuery('SELECT u FROM ' . CmsUser::class . ' u')
            ->getResult(GH12272GeneratedMappingCapturingHydrator::class);

        self::assertSame([true], GH12272GeneratedMappingCapturingHydrator::$observedGeneratedMappings);
    }

    #[Group('GH-12272')]
    public function testDqlIterableMarksGeneratedResultSetMappingBeforeHydration(): void
    {
        GH12272GeneratedMappingCapturingHydrator::reset();

        $entityManager = $this->createCmsEntityManagerWithDriverResult(
            new OraclePlatform(),
            ArrayResultFactory::createDriverResultFromArray([['ID0' => '1']]),
        );
        $entityManager->getConfiguration()->addCustomHydrationMode(
            GH12272GeneratedMappingCapturingHydrator::class,
            GH12272GeneratedMappingCapturingHydrator::class,
        );

        iterator_to_array(
            $entityManager->createQuery('SELECT u FROM ' . CmsUser::class . ' u')
                ->toIterable([], GH12272GeneratedMappingCapturingHydrator::class),
            false,
        );

        self::assertSame([true], GH12272GeneratedMappingCapturingHydrator::$observedGeneratedMappings);
    }

    #[Group('GH-12272')]
    public function testExplicitDqlResultSetMappingRemainsUserOwned(): void
    {
        GH12272GeneratedMappingCapturingHydrator::reset();

        $entityManager = $this->createCmsEntityManagerWithDriverResult(
            new OraclePlatform(),
            ArrayResultFactory::createDriverResultFromArray([['ID0' => '1']]),
        );
        $entityManager->getConfiguration()->addCustomHydrationMode(
            GH12272GeneratedMappingCapturingHydrator::class,
            GH12272GeneratedMappingCapturingHydrator::class,
        );

        $entityManager->createQuery('SELECT u FROM ' . CmsUser::class . ' u')
            ->setResultSetMapping(new ResultSetMapping())
            ->getResult(GH12272GeneratedMappingCapturingHydrator::class);

        self::assertSame([false], GH12272GeneratedMappingCapturingHydrator::$observedGeneratedMappings);
    }

    #[Group('GH-12272')]
    public function testBasicEntityPersisterMarksGeneratedResultSetMappingBeforeHydration(): void
    {
        GH12272GeneratedMappingCapturingHydrator::reset();

        $entityManager = $this->createCapturingCmsEntityManagerWithExecutedDriverResult(
            new OraclePlatform(),
            ArrayResultFactory::createDriverResultFromArray([['ID0' => '1']]),
        );
        $persister     = new BasicEntityPersister($entityManager, $entityManager->getClassMetadata(CmsUser::class));

        $persister->loadAll();

        self::assertSame([true], GH12272GeneratedMappingCapturingHydrator::$observedGeneratedMappings);
    }

    #[Group('GH-12272')]
    public function testGeneratedObjectHydrationUsesActualDbalResultColumnNames(): void
    {
        $this->requireDbalPortabilityResultColumnNames();

        $entityManager = $this->createCmsEntityManagerWithPlatform(new OraclePlatform());
        $rsm           = $this->createGeneratedCmsUserResultSetMapping($entityManager);
        $stmt          = $this->createPortableLowerCaseResult($this->createUpperCaseCmsUserRows(), $entityManager->getConnection());
        $hydrator      = new ObjectHydrator($entityManager);

        $result = $hydrator->hydrateAll($stmt, $rsm);

        self::assertCount(1, $result);
        self::assertInstanceOf(CmsUser::class, $result[0]);
        self::assertSame(1, $result[0]->getId());
        self::assertSame('Roman', $result[0]->getName());
        self::assertSame('romanb', $result[0]->getUsername());
    }

    #[Group('GH-12272')]
    public function testGeneratedObjectHydrationUsesActualDbalResultColumnNamesForDiscriminator(): void
    {
        $this->requireDbalPortabilityResultColumnNames();

        $entityManager            = $this->createCompanyEntityManagerWithPlatform(new OraclePlatform());
        $rsm                      = new ResultSetMapping();
        $rsm->isInternalGenerated = true;
        $rsm->addEntityResult(CompanyPerson::class, 'p');
        $rsm->addFieldResult('p', 'P__ID', 'id');
        $rsm->addFieldResult('p', 'P__NAME', 'name');
        $rsm->addMetaResult('p', 'DISCR', 'discr', false, 'string');
        $rsm->setDiscriminatorColumn('p', 'DISCR');

        $stmt     = $this->createPortableLowerCaseResult(
            [
                [
                    'P__ID' => '1',
                    'P__NAME' => 'Alex',
                    'DISCR' => 'person',
                ],
            ],
            $entityManager->getConnection(),
        );
        $hydrator = new ObjectHydrator($entityManager);
        $result   = $hydrator->hydrateAll($stmt, $rsm);

        self::assertCount(1, $result);
        self::assertInstanceOf(CompanyPerson::class, $result[0]);
        self::assertSame('Alex', $result[0]->getName());
    }

    #[Group('GH-12272')]
    public function testGeneratedArrayHydrationUsesActualDbalResultColumnNamesForIndexBy(): void
    {
        $this->requireDbalPortabilityResultColumnNames();

        $entityManager = $this->createCmsEntityManagerWithPlatform(new OraclePlatform());
        $rsm           = $this->createGeneratedCmsUserResultSetMapping($entityManager);
        $rsm->addIndexBy('u', 'id');

        $stmt     = $this->createPortableLowerCaseResult($this->createUpperCaseCmsUserRows(), $entityManager->getConnection());
        $hydrator = new ArrayHydrator($entityManager);
        $result   = $hydrator->hydrateAll($stmt, $rsm);

        self::assertArrayHasKey(1, $result);
        self::assertSame(1, $result[1]['id']);
        self::assertSame('Roman', $result[1]['name']);
    }

    #[Group('GH-12272')]
    public function testGeneratedObjectHydrationUsesActualDbalResultColumnNamesForIndexBy(): void
    {
        $this->requireDbalPortabilityResultColumnNames();

        $entityManager = $this->createCmsEntityManagerWithPlatform(new OraclePlatform());
        $rsm           = $this->createGeneratedCmsUserResultSetMapping($entityManager);
        $rsm->addIndexBy('u', 'id');

        $stmt     = $this->createPortableLowerCaseResult($this->createUpperCaseCmsUserRows(), $entityManager->getConnection());
        $hydrator = new ObjectHydrator($entityManager);
        $result   = $hydrator->hydrateAll($stmt, $rsm);

        self::assertArrayHasKey(1, $result);
        self::assertInstanceOf(CmsUser::class, $result[1]);
        self::assertSame('Roman', $result[1]->getName());
    }

    #[Group('GH-12272')]
    public function testGeneratedArrayHydrationUsesActualDbalResultColumnNamesForScalarIndexBy(): void
    {
        $this->requireDbalPortabilityResultColumnNames();

        $entityManager            = $this->createCmsEntityManagerWithPlatform(new OraclePlatform());
        $rsm                      = new ResultSetMapping();
        $rsm->isInternalGenerated = true;
        $rsm->addScalarResult('ID0', 'id', 'integer');
        $rsm->addScalarResult('NAME1', 'name', 'string');
        $rsm->addIndexByScalar('ID0');

        $stmt     = $this->createPortableLowerCaseResult(
            [
                [
                    'ID0' => '1',
                    'NAME1' => 'Roman',
                ],
            ],
            $entityManager->getConnection(),
        );
        $hydrator = new ArrayHydrator($entityManager);
        $result   = $hydrator->hydrateAll($stmt, $rsm);

        self::assertArrayHasKey(1, $result);
        self::assertSame(1, $result[1]['id']);
        self::assertSame('Roman', $result[1]['name']);
    }

    #[Group('GH-12272')]
    public function testGeneratedObjectHydrationUsesActualDbalResultColumnNamesForScalarIndexBy(): void
    {
        $this->requireDbalPortabilityResultColumnNames();

        $entityManager            = $this->createCmsEntityManagerWithPlatform(new OraclePlatform());
        $rsm                      = new ResultSetMapping();
        $rsm->isInternalGenerated = true;
        $rsm->addScalarResult('ID0', 'id', 'integer');
        $rsm->addScalarResult('NAME1', 'name', 'string');
        $rsm->addIndexByScalar('ID0');

        $stmt     = $this->createPortableLowerCaseResult(
            [
                [
                    'ID0' => '1',
                    'NAME1' => 'Roman',
                ],
            ],
            $entityManager->getConnection(),
        );
        $hydrator = new ObjectHydrator($entityManager);
        $result   = $hydrator->hydrateAll($stmt, $rsm);

        self::assertArrayHasKey(1, $result);
        self::assertSame(1, $result[1]['id']);
        self::assertSame('Roman', $result[1]['name']);
    }

    #[Group('GH-12272')]
    public function testGeneratedArrayHydrationUsesActualDbalResultColumnNamesForJoinedCollectionIndexBy(): void
    {
        $this->requireDbalPortabilityResultColumnNames();

        $entityManager = $this->createCmsEntityManagerWithPlatform(new OraclePlatform());
        $rsm           = $this->createGeneratedIndexedUserPhoneResultSetMapping();

        $stmt     = $this->createPortableLowerCaseResult($this->createUpperCaseIndexedUserPhoneRows(), $entityManager->getConnection());
        $hydrator = new ArrayHydrator($entityManager);
        $result   = $hydrator->hydrateAll($stmt, $rsm);

        self::assertArrayHasKey(1, $result);
        self::assertArrayHasKey('42', $result[1]['phonenumbers']);
        self::assertSame('42', $result[1]['phonenumbers']['42']['phonenumber']);
    }

    #[Group('GH-12272')]
    public function testGeneratedObjectHydrationUsesActualDbalResultColumnNamesForJoinedCollectionIndexBy(): void
    {
        $this->requireDbalPortabilityResultColumnNames();

        $entityManager = $this->createCmsEntityManagerWithPlatform(new OraclePlatform());
        $rsm           = $this->createGeneratedIndexedUserPhoneResultSetMapping();

        $stmt     = $this->createPortableLowerCaseResult($this->createUpperCaseIndexedUserPhoneRows(), $entityManager->getConnection());
        $hydrator = new ObjectHydrator($entityManager);
        $result   = $hydrator->hydrateAll($stmt, $rsm);

        self::assertArrayHasKey(1, $result);
        self::assertTrue(isset($result[1]->getPhonenumbers()['42']));
        self::assertSame('42', $result[1]->getPhonenumbers()['42']->phonenumber);
    }

    #[Group('GH-12272')]
    public function testGeneratedSimpleObjectHydrationUsesActualDbalResultColumnNamesForDiscriminator(): void
    {
        $this->requireDbalPortabilityResultColumnNames();

        $entityManager            = $this->createCompanyEntityManagerWithPlatform(new OraclePlatform());
        $rsm                      = new ResultSetMapping();
        $rsm->isInternalGenerated = true;
        $rsm->addEntityResult(CompanyPerson::class, 'p');
        $rsm->addFieldResult('p', 'P__ID', 'id');
        $rsm->addFieldResult('p', 'P__NAME', 'name');
        $rsm->addMetaResult('p', 'DISCR', 'discr', false, 'string');
        $rsm->setDiscriminatorColumn('p', 'DISCR');

        $stmt     = $this->createPortableLowerCaseResult(
            [
                [
                    'P__ID' => '1',
                    'P__NAME' => 'Alex',
                    'DISCR' => 'person',
                ],
            ],
            $entityManager->getConnection(),
        );
        $hydrator = new SimpleObjectHydrator($entityManager);
        $result   = $hydrator->hydrateAll($stmt, $rsm);

        self::assertCount(1, $result);
        self::assertInstanceOf(CompanyPerson::class, $result[0]);
        self::assertSame('Alex', $result[0]->getName());
    }

    #[Group('GH-12272')]
    public function testGeneratedSingleScalarHydrationUsesActualDbalResultColumnNames(): void
    {
        $this->requireDbalPortabilityResultColumnNames();

        $entityManager            = $this->createCmsEntityManagerWithPlatform(new OraclePlatform());
        $rsm                      = new ResultSetMapping();
        $rsm->isInternalGenerated = true;
        $rsm->addScalarResult('ID0', 'id', 'integer');

        $stmt     = $this->createPortableLowerCaseResult([['ID0' => '1']], $entityManager->getConnection());
        $hydrator = new SingleScalarHydrator($entityManager);

        self::assertSame('1', $hydrator->hydrateAll($stmt, $rsm));
    }

    #[Group('GH-12272')]
    public function testGeneratedEnumMappingUsesActualDbalResultColumnNames(): void
    {
        $this->requireDbalPortabilityResultColumnNames();

        $entityManager            = $this->createCmsEntityManagerWithPlatform(new OraclePlatform());
        $rsm                      = new ResultSetMapping();
        $rsm->isInternalGenerated = true;
        $rsm->addScalarResult('SUIT0', 'suit', 'string');
        $rsm->addEnumResult('SUIT0', Suit::class);

        $stmt     = $this->createPortableLowerCaseResult([['SUIT0' => 'H']], $entityManager->getConnection());
        $hydrator = new ScalarHydrator($entityManager);

        self::assertSame(
            [['suit' => 'H']],
            $hydrator->hydrateAll($stmt, $rsm),
        );
    }

    #[Group('GH-12272')]
    public function testGeneratedNewObjectMappingsUseActualDbalResultColumnNames(): void
    {
        $this->requireDbalPortabilityResultColumnNames();

        $entityManager            = $this->createCmsEntityManagerWithPlatform(new OraclePlatform());
        $rsm                      = new ResultSetMapping();
        $rsm->isInternalGenerated = true;
        $rsm->addScalarResult('NAME0', 1, 'string');
        $rsm->addScalarResult('EMAIL1', 2, 'string');
        $rsm->addScalarResult('CITY2', 3, 'string');
        $rsm->newObjectMappings['NAME0']  = [
            'className' => CmsUserDTO::class,
            'objIndex' => 0,
            'argIndex' => 0,
        ];
        $rsm->newObjectMappings['EMAIL1'] = [
            'className' => CmsUserDTO::class,
            'objIndex' => 0,
            'argIndex' => 1,
        ];
        $rsm->newObjectMappings['CITY2']  = [
            'className' => CmsUserDTO::class,
            'objIndex' => 0,
            'argIndex' => 2,
        ];

        $stmt     = $this->createPortableLowerCaseResult(
            [
                [
                    'NAME0' => 'Roman',
                    'EMAIL1' => 'roman@example.com',
                    'CITY2' => 'Berlin',
                ],
            ],
            $entityManager->getConnection(),
        );
        $hydrator = new ObjectHydrator($entityManager);
        $result   = $hydrator->hydrateAll($stmt, $rsm);

        self::assertCount(1, $result);
        self::assertInstanceOf(CmsUserDTO::class, $result[0]);
        self::assertSame('Roman', $result[0]->name);
        self::assertSame('roman@example.com', $result[0]->email);
        self::assertSame('Berlin', $result[0]->address);
    }

    #[Group('GH-12272')]
    public function testGeneratedAliasReconciliationDoesNotMutateResultSetMapping(): void
    {
        $this->requireDbalPortabilityResultColumnNames();

        $entityManager        = $this->createCmsEntityManagerWithPlatform(new OraclePlatform());
        $rsm                  = $this->createGeneratedCmsUserResultSetMapping($entityManager);
        $originalFieldMapping = $rsm->fieldMappings;
        $originalOwnerMap     = $rsm->columnOwnerMap;
        $stmt                 = $this->createPortableLowerCaseResult($this->createUpperCaseCmsUserRows(), $entityManager->getConnection());
        $hydrator             = new ScalarHydrator($entityManager);

        $hydrator->hydrateAll($stmt, $rsm);

        self::assertSame($originalFieldMapping, $rsm->fieldMappings);
        self::assertSame($originalOwnerMap, $rsm->columnOwnerMap);
        self::assertArrayHasKey('ID0', $rsm->fieldMappings);
        self::assertArrayNotHasKey('id0', $rsm->fieldMappings);
    }

    #[Group('GH-12272')]
    public function testNativeResultSetMappingRemainsCaseSensitiveWhenDbalPortabilityLowerCasesKeys(): void
    {
        $this->requireDbalPortabilityResultColumnNames();

        $entityManager = $this->createCmsEntityManagerWithPlatform(new OraclePlatform());
        $rsm           = new ResultSetMapping();
        $rsm->addEntityResult(CmsUser::class, 'u');
        $rsm->addFieldResult('u', 'ID0', 'id');

        $stmt     = $this->createPortableLowerCaseResult([['ID0' => '1']], $entityManager->getConnection());
        $hydrator = new ScalarHydrator($entityManager);

        self::assertSame([[]], $hydrator->hydrateAll($stmt, $rsm));
    }

    #[Group('GH-12272')]
    public function testGeneratedAliasReconciliationDoesNotGuessAmbiguousCaseFoldedAliases(): void
    {
        $entityManager            = $this->createCmsEntityManagerWithPlatform(new OraclePlatform());
        $rsm                      = new ResultSetMapping();
        $rsm->isInternalGenerated = true;
        $rsm->addEntityResult(CmsUser::class, 'u');
        $rsm->addFieldResult('u', 'ID0', 'id');
        $rsm->addFieldResult('u', 'Id0', 'name');

        $stmt     = ArrayResultFactory::createWrapperResultFromArray([['id0' => '1']], $entityManager->getConnection());
        $hydrator = new ScalarHydrator($entityManager);

        self::assertSame([[]], $hydrator->hydrateAll($stmt, $rsm));
    }

    #[Group('GH-12272')]
    public function testGeneratedAliasReconciliationPrefersExactResultSetMappingMatch(): void
    {
        $entityManager            = $this->createCmsEntityManagerWithPlatform(new OraclePlatform());
        $rsm                      = new ResultSetMapping();
        $rsm->isInternalGenerated = true;
        $rsm->addEntityResult(CmsUser::class, 'u');
        $rsm->addFieldResult('u', 'ID0', 'id');
        $rsm->addScalarResult('id0', 'lower_id', 'string');

        $stmt     = ArrayResultFactory::createWrapperResultFromArray([['id0' => 'lower']], $entityManager->getConnection());
        $hydrator = new ScalarHydrator($entityManager);

        self::assertSame(
            [['lower_id' => 'lower']],
            $hydrator->hydrateAll($stmt, $rsm),
        );
    }

    #[Group('GH-12272')]
    public function testGeneratedAliasReconciliationFallsBackWhenResultColumnNamesAreUnavailable(): void
    {
        $entityManager = $this->createCmsEntityManagerWithPlatform(new OraclePlatform());
        $rsm           = $this->createGeneratedCmsUserResultSetMapping($entityManager);
        $stmt          = $this->createResultWithoutColumnNames([['id0' => '1']], $entityManager->getConnection());
        $hydrator      = new ScalarHydrator($entityManager);

        self::assertSame([[]], $hydrator->hydrateAll($stmt, $rsm));
    }

    private function requireDbalPortabilityResultColumnNames(): void
    {
        if (class_exists(Converter::class) && class_exists(PortabilityResult::class) && (new ReflectionClass(DbalResult::class))->hasMethod('getColumnName')) {
            return;
        }

        self::markTestSkipped('This test requires DBAL portability conversion and result column name support.');
    }

    private function createCmsEntityManagerWithPlatform(AbstractPlatform $platform): EntityManagerMock
    {
        $entityManager = $this->createTestEntityManagerWithPlatform($platform);
        $entityManager->getConfiguration()->setMetadataDriverImpl(
            AttributeDriverFactory::createAttributeDriver([__DIR__ . '/../../../Models/CMS']),
        );

        return $entityManager;
    }

    private function createCmsEntityManagerWithDriverResult(AbstractPlatform $platform, DriverResult $result): EntityManagerMock
    {
        $driverConnection = $this->createStub(Driver\Connection::class);
        $driverConnection->method('query')
            ->willReturn($result);

        $driver = $this->createStub(Driver::class);
        $driver->method('connect')
            ->willReturn($driverConnection);
        $driver->method('getDatabasePlatform')
            ->willReturn($platform);

        $entityManager = $this->createTestEntityManagerWithConnection(new Connection([], $driver));
        $entityManager->getConfiguration()->setMetadataDriverImpl(
            AttributeDriverFactory::createAttributeDriver([__DIR__ . '/../../../Models/CMS']),
        );

        return $entityManager;
    }

    private function createCapturingCmsEntityManagerWithExecutedDriverResult(
        AbstractPlatform $platform,
        DriverResult $result,
    ): EntityManagerMock {
        $driver = $this->createStub(Driver::class);
        $driver->method('connect')
            ->willReturn($this->createStub(Driver\Connection::class));
        $driver->method('getDatabasePlatform')
            ->willReturn($platform);

        $connection = $this->getMockBuilder(Connection::class)
            ->setConstructorArgs([[], $driver])
            ->onlyMethods(['executeQuery', 'getDatabasePlatform'])
            ->getMock();
        $connection->method('executeQuery')
            ->willReturn(new DbalResult($result, $connection));
        $connection->method('getDatabasePlatform')
            ->willReturn($platform);

        $entityManager = $this->getMockBuilder(EntityManagerMock::class)
            ->setConstructorArgs([$connection])
            ->onlyMethods(['newHydrator'])
            ->getMock();
        $entityManager->getConfiguration()->setMetadataDriverImpl(
            AttributeDriverFactory::createAttributeDriver([__DIR__ . '/../../../Models/CMS']),
        );
        $entityManager->method('newHydrator')
            ->willReturnCallback(static fn () => new GH12272GeneratedMappingCapturingHydrator($entityManager));

        return $entityManager;
    }

    private function createCompanyEntityManagerWithPlatform(AbstractPlatform $platform): EntityManagerMock
    {
        $entityManager = $this->createTestEntityManagerWithPlatform($platform);
        $entityManager->getConfiguration()->setMetadataDriverImpl(
            AttributeDriverFactory::createAttributeDriver([__DIR__ . '/../../../Models/Company']),
        );

        return $entityManager;
    }

    private function createGeneratedCmsUserResultSetMapping(EntityManagerMock $entityManager): ResultSetMappingBuilder
    {
        $rsm                      = new ResultSetMappingBuilder($entityManager, ResultSetMappingBuilder::COLUMN_RENAMING_INCREMENT);
        $rsm->isInternalGenerated = true;
        $rsm->addRootEntityFromClassMetadata(CmsUser::class, 'u');

        self::assertSame(['ID0', 'STATUS1', 'USERNAME2', 'NAME3'], array_keys($rsm->fieldMappings));
        self::assertArrayHasKey('EMAIL_ID4', $rsm->metaMappings);

        return $rsm;
    }

    private function createGeneratedIndexedUserPhoneResultSetMapping(): ResultSetMapping
    {
        $rsm                      = new ResultSetMapping();
        $rsm->isInternalGenerated = true;
        $rsm->addEntityResult(CmsUser::class, 'u');
        $rsm->addJoinedEntityResult(CmsPhonenumber::class, 'p', 'u', 'phonenumbers');
        $rsm->addFieldResult('u', 'U__ID', 'id');
        $rsm->addFieldResult('u', 'U__STATUS', 'status');
        $rsm->addFieldResult('p', 'P__PHONENUMBER', 'phonenumber');
        $rsm->addIndexBy('u', 'id');
        $rsm->addIndexBy('p', 'phonenumber');

        return $rsm;
    }

    /** @return list<array<string, mixed>> */
    private function createUpperCaseCmsUserRows(): array
    {
        return [
            [
                'ID0' => '1',
                'STATUS1' => 'active',
                'USERNAME2' => 'romanb',
                'NAME3' => 'Roman',
                'EMAIL_ID4' => null,
            ],
        ];
    }

    /** @return list<array<string, mixed>> */
    private function createUpperCaseIndexedUserPhoneRows(): array
    {
        return [
            [
                'U__ID' => '1',
                'U__STATUS' => 'active',
                'P__PHONENUMBER' => '42',
            ],
        ];
    }

    /** @param list<array<string, mixed>> $rows */
    private function createPortableLowerCaseResult(array $rows, Connection $connection): DbalResult
    {
        return new DbalResult(
            new PortabilityResult(
                ArrayResultFactory::createDriverResultFromArray($rows),
                new Converter(false, false, Converter::CASE_LOWER),
            ),
            $connection,
        );
    }

    /** @param list<array<string, mixed>> $rows */
    private function createResultWithoutColumnNames(array $rows, Connection $connection): DbalResult
    {
        $driverResult = $this->createMock(DriverResult::class);
        $driverResult->method('fetchAssociative')
            ->willReturnOnConsecutiveCalls($rows[0], false);
        $driverResult->method('fetchAllAssociative')
            ->willReturn($rows);
        $driverResult->method('fetchFirstColumn')
            ->willReturn([]);
        $driverResult->method('columnCount')
            ->willReturn(1);

        return new DbalResult($driverResult, $connection);
    }
}

final class GH12272GeneratedMappingCapturingHydrator extends AbstractHydrator
{
    /** @var list<bool> */
    public static array $observedGeneratedMappings = [];

    public static function reset(): void
    {
        self::$observedGeneratedMappings = [];
    }

    /** @return list<mixed> */
    protected function hydrateAllData(): array
    {
        self::$observedGeneratedMappings[] = $this->resultSetMapping()->isInternalGenerated;

        return [];
    }

    /** @param array<string, mixed> $row */
    protected function hydrateRowData(array $row, array &$result): void
    {
        self::$observedGeneratedMappings[] = $this->resultSetMapping()->isInternalGenerated;
        $result[]                          = $row;
    }
}
