<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\DB2Platform;
use Doctrine\DBAL\Platforms\OraclePlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\ORM\Internal\Hydration\ScalarHydrator;
use Doctrine\ORM\Internal\Hydration\SimpleObjectHydrator;
use Doctrine\ORM\Mapping\AnsiQuoteStrategy;
use Doctrine\ORM\Mapping\DefaultQuoteStrategy;
use Doctrine\ORM\Persisters\Entity\JoinedSubclassPersister;
use Doctrine\ORM\Persisters\Entity\SingleTablePersister;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Tests\Mocks\ArrayResultFactory;
use Doctrine\Tests\Mocks\AttributeDriverFactory;
use Doctrine\Tests\Mocks\EntityManagerMock;
use Doctrine\Tests\Models\CMS\CmsUser;
use Doctrine\Tests\Models\Company\CompanyContract;
use Doctrine\Tests\Models\Company\CompanyPerson;
use Doctrine\Tests\OrmTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionMethod;

use function constant;
use function enum_exists;

final class GH12272Test extends OrmTestCase
{
    #[Group('GH-12272')]
    public function testDefaultPlatformAliasCasingRemainsUnchanged(): void
    {
        $quoteStrategy = new DefaultQuoteStrategy();

        self::assertSame('IDPAIS_0', $quoteStrategy->getColumnAlias('idpais', 0, new OraclePlatform()));
        self::assertSame('IDPAIS_0', $quoteStrategy->getColumnAlias('idpais', 0, new DB2Platform()));
        self::assertSame('idpais_0', $quoteStrategy->getColumnAlias('idpais', 0, new PostgreSQLPlatform()));
    }

    /** @phpstan-param 'UPPER'|'LOWER'|'NONE' $foldingName */
    #[DataProvider('provideIdentifierFoldings')]
    #[Group('GH-12272')]
    public function testQuoteStrategyUsesPlatformIdentifierFoldingWhenAvailable(
        string $foldingName,
        string $expectedAlias,
    ): void {
        $this->requireDbalIdentifierFolding();

        self::assertSame(
            $expectedAlias,
            (new DefaultQuoteStrategy())->getColumnAlias(
                'idPais',
                0,
                $this->createOraclePlatformWithIdentifierFolding($foldingName),
            ),
        );
    }

    /** @phpstan-param 'UPPER'|'LOWER'|'NONE' $foldingName */
    #[DataProvider('provideIdentifierFoldings')]
    #[Group('GH-12272')]
    public function testAnsiQuoteStrategyUsesPlatformIdentifierFoldingWhenAvailable(
        string $foldingName,
        string $expectedAlias,
    ): void {
        $this->requireDbalIdentifierFolding();

        self::assertSame(
            $expectedAlias,
            (new AnsiQuoteStrategy())->getColumnAlias(
                'idPais',
                0,
                $this->createOraclePlatformWithIdentifierFolding($foldingName),
            ),
        );
    }

    /** @phpstan-return iterable<string, array{string, string}> */
    public static function provideIdentifierFoldings(): iterable
    {
        yield 'upper' => ['UPPER', 'IDPAIS_0'];
        yield 'lower' => ['LOWER', 'idpais_0'];
        yield 'none' => ['NONE', 'idPais_0'];
    }

    #[Group('GH-12272')]
    public function testResultSetMappingBuilderUsesPlatformIdentifierFoldingForGeneratedAliases(): void
    {
        $this->requireDbalIdentifierFolding();

        $rsm = new ResultSetMappingBuilder(
            $this->createEntityManagerWithPlatform($this->createOraclePlatformWithIdentifierFolding('LOWER')),
            ResultSetMappingBuilder::COLUMN_RENAMING_INCREMENT,
        );
        $rsm->addRootEntityFromClassMetadata(CmsUser::class, 'u');

        self::assertArrayHasKey('id0', $rsm->fieldMappings);
        self::assertArrayNotHasKey('ID0', $rsm->fieldMappings);
        self::assertSame(
            'u.id AS id0, u.status AS status1, u.username AS username2, u.name AS name3, u.email_id AS email_id4',
            $rsm->generateSelectClause(),
        );
    }

    #[Group('GH-12272')]
    public function testHydratorReadsRowsUsingAliasesFromPlatformIdentifierFolding(): void
    {
        $this->requireDbalIdentifierFolding();

        $entityManager = $this->createEntityManagerWithPlatform(
            $this->createOraclePlatformWithIdentifierFolding('LOWER'),
        );
        $rsm           = new ResultSetMappingBuilder($entityManager, ResultSetMappingBuilder::COLUMN_RENAMING_INCREMENT);
        $rsm->addRootEntityFromClassMetadata(CmsUser::class, 'u');

        $stmt     = ArrayResultFactory::createWrapperResultFromArray(
            [
                [
                    'id0' => '1',
                    'status1' => 'active',
                    'username2' => 'romanb',
                    'name3' => 'Roman',
                    'email_id4' => null,
                ],
            ],
            $entityManager->getConnection(),
        );
        $hydrator = new ScalarHydrator($entityManager);

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
    public function testHydratorKeepsResultSetMappingColumnNamesCaseSensitive(): void
    {
        $this->requireDbalIdentifierFolding();

        $entityManager = $this->createEntityManagerWithPlatform(
            $this->createOraclePlatformWithIdentifierFolding('LOWER'),
        );
        $rsm           = new ResultSetMappingBuilder($entityManager, ResultSetMappingBuilder::COLUMN_RENAMING_INCREMENT);
        $rsm->addRootEntityFromClassMetadata(CmsUser::class, 'u');

        $stmt     = ArrayResultFactory::createWrapperResultFromArray(
            [
                [
                    'ID0' => '1',
                    'STATUS1' => 'active',
                    'USERNAME2' => 'romanb',
                    'NAME3' => 'Roman',
                    'EMAIL_ID4' => null,
                ],
            ],
            $entityManager->getConnection(),
        );
        $hydrator = new ScalarHydrator($entityManager);

        self::assertSame([[]], $hydrator->hydrateAll($stmt, $rsm));
    }

    #[Group('GH-12272')]
    public function testPaginatorUsesPlatformIdentifierFoldingForCountResultAlias(): void
    {
        $this->requireDbalIdentifierFolding();

        $entityManager = $this->createEntityManagerWithPlatform(
            $this->createOraclePlatformWithIdentifierFolding('LOWER'),
        );
        $query         = $entityManager->createQuery('SELECT u FROM ' . CmsUser::class . ' u');
        $countQuery    = $this->getPaginatorCountQuery(new Paginator($query));
        $rsm           = $this->getQueryResultSetMapping($countQuery);

        self::assertSame('count', $rsm->scalarMappings['dctrn_count']);
        self::assertArrayNotHasKey('DCTRN_COUNT', $rsm->scalarMappings);
    }

    #[Group('GH-12272')]
    public function testSimpleObjectHydratorUsesPlatformIdentifierFoldingForDiscriminatorColumn(): void
    {
        $this->requireDbalIdentifierFolding();

        $entityManager = $this->createCompanyEntityManagerWithPlatform(
            $this->createOraclePlatformWithIdentifierFolding('LOWER'),
        );
        $rsm           = new ResultSetMapping();
        $rsm->addEntityResult(CompanyPerson::class, 'p');
        $rsm->addFieldResult('p', 'p__id', 'id');
        $rsm->addFieldResult('p', 'p__name', 'name');
        $rsm->setDiscriminatorColumn('p', 'discr');

        $stmt     = ArrayResultFactory::createWrapperResultFromArray(
            [
                [
                    'p__id' => '1',
                    'p__name' => 'Alex',
                    'discr' => 'person',
                ],
            ],
            $entityManager->getConnection(),
        );
        $hydrator = new SimpleObjectHydrator($entityManager);
        $result   = $hydrator->hydrateAll($stmt, $rsm);

        self::assertInstanceOf(CompanyPerson::class, $result[0]);
        self::assertSame('Alex', $result[0]->getName());
    }

    #[Group('GH-12272')]
    public function testSingleTablePersisterUsesPlatformIdentifierFoldingForDiscriminatorAlias(): void
    {
        $this->requireDbalIdentifierFolding();

        $entityManager = $this->createCompanyEntityManagerWithPlatform(
            $this->createOraclePlatformWithIdentifierFolding('LOWER'),
        );
        $persister     = new SingleTablePersister(
            $entityManager,
            $entityManager->getClassMetadata(CompanyContract::class),
        );

        $this->getPersisterSelectColumnsSql($persister);
        $rsm = $persister->getResultSetMapping();

        self::assertSame('discr', $rsm->discriminatorColumns['r']);
        self::assertSame('discr', $rsm->metaMappings['discr']);
        self::assertArrayNotHasKey('DISCR', $rsm->metaMappings);
    }

    #[Group('GH-12272')]
    public function testJoinedSubclassPersisterUsesPlatformIdentifierFoldingForDiscriminatorAlias(): void
    {
        $this->requireDbalIdentifierFolding();

        $entityManager = $this->createCompanyEntityManagerWithPlatform(
            $this->createOraclePlatformWithIdentifierFolding('LOWER'),
        );
        $persister     = new JoinedSubclassPersister(
            $entityManager,
            $entityManager->getClassMetadata(CompanyPerson::class),
        );

        $this->getPersisterSelectColumnsSql($persister);
        $rsm = $persister->getResultSetMapping();

        self::assertSame('discr', $rsm->discriminatorColumns['r']);
        self::assertSame('discr', $rsm->metaMappings['discr']);
        self::assertArrayNotHasKey('DISCR', $rsm->metaMappings);
    }

    private function requireDbalIdentifierFolding(): void
    {
        if (enum_exists('Doctrine\DBAL\Schema\Name\UnquotedIdentifierFolding')) {
            return;
        }

        self::markTestSkipped('This test requires doctrine/dbal with UnquotedIdentifierFolding support.');
    }

    /** @phpstan-param 'UPPER'|'LOWER'|'NONE' $foldingName */
    private function createOraclePlatformWithIdentifierFolding(string $foldingName): OraclePlatform&MockObject
    {
        $platform = $this->getMockBuilder(OraclePlatform::class)
            ->onlyMethods(['getUnquotedIdentifierFolding'])
            ->getMock();

        $platform->method('getUnquotedIdentifierFolding')
            ->willReturn(constant('Doctrine\DBAL\Schema\Name\UnquotedIdentifierFolding::' . $foldingName));

        return $platform;
    }

    private function createEntityManagerWithPlatform(AbstractPlatform $platform): EntityManagerMock
    {
        $entityManager = $this->createTestEntityManagerWithPlatform($platform);
        $entityManager->getConfiguration()->setMetadataDriverImpl(
            AttributeDriverFactory::createAttributeDriver([__DIR__ . '/../../../Models/CMS']),
        );

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

    /** @phpstan-param Paginator<object> $paginator */
    private function getPaginatorCountQuery(Paginator $paginator): Query
    {
        $getCountQuery = new ReflectionMethod($paginator, 'getCountQuery');

        return $getCountQuery->invoke($paginator);
    }

    private function getQueryResultSetMapping(Query $query): ResultSetMapping
    {
        $getResultSetMapping = new ReflectionMethod($query, 'getResultSetMapping');

        return $getResultSetMapping->invoke($query);
    }

    private function getPersisterSelectColumnsSql(SingleTablePersister|JoinedSubclassPersister $persister): string
    {
        $getSelectColumnsSql = new ReflectionMethod($persister, 'getSelectColumnsSQL');

        return $getSelectColumnsSql->invoke($persister);
    }
}
