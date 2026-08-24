<?php

declare(strict_types=1);

namespace Doctrine\ORM\Internal\Hydration;

use BackedEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Result;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Tools\Pagination\LimitSubqueryWalker;
use Doctrine\ORM\UnitOfWork;
use Generator;
use LogicException;
use ReflectionClass;
use ReflectionEnum;
use Throwable;

use function array_key_exists;
use function array_keys;
use function array_map;
use function array_merge;
use function count;
use function current;
use function end;
use function in_array;
use function is_array;
use function is_object;
use function ksort;
use function strtolower;

/**
 * Base class for all hydrators. A hydrator is a class that provides some form
 * of transformation of an SQL result set into another structure.
 *
 * @phpstan-consistent-constructor
 */
abstract class AbstractHydrator
{
    /**
     * The ResultSetMapping.
     */
    protected ResultSetMapping|null $rsm = null;

    /**
     * The dbms Platform instance.
     */
    protected AbstractPlatform $platform;

    /**
     * The UnitOfWork of the associated EntityManager.
     */
    protected UnitOfWork $uow;

    /**
     * Local ClassMetadata cache to avoid going to the EntityManager all the time.
     *
     * @var array<string, ClassMetadata<object>>
     */
    protected array $metadataCache = [];

    /**
     * The cache used during row-by-row hydration.
     *
     * @var array<string, mixed[]|null>
     */
    protected array $cache = [];

    /**
     * The statement that provides the data to hydrate.
     */
    protected Result|null $stmt = null;

    /**
     * The query hints.
     *
     * @var array<string, mixed>
     */
    protected array $hints = [];

    /**
     * Maps actual result-set column names to ResultSetMapping column names.
     *
     * @var array<string, string>
     */
    private array $mappedColumnNames = [];

    /**
     * Maps ResultSetMapping column names to actual result-set column names.
     *
     * @var array<string, string>
     */
    private array $resultColumnNames = [];

    /**
     * Initializes a new instance of a class derived from <tt>AbstractHydrator</tt>.
     */
    public function __construct(protected EntityManagerInterface $em)
    {
        $this->platform = $em->getConnection()->getDatabasePlatform();
        $this->uow      = $em->getUnitOfWork();
    }

    /**
     * Initiates a row-by-row hydration.
     *
     * @phpstan-param array<string, mixed> $hints
     *
     * @return Generator<array-key, mixed>
     *
     * @final
     */
    final public function toIterable(Result $stmt, ResultSetMapping $resultSetMapping, array $hints = []): Generator
    {
        $this->stmt  = $stmt;
        $this->rsm   = $resultSetMapping;
        $this->hints = $hints;

        $evm = $this->em->getEventManager();

        $evm->addEventListener([Events::onClear], $this);

        $this->prepare();

        try {
            while (true) {
                $row = $this->statement()->fetchAssociative();

                if ($row === false) {
                    break;
                }

                $result = [];

                $this->hydrateRowData($row, $result);

                $this->cleanupAfterRowIteration();
                if (count($result) === 1) {
                    if (count($resultSetMapping->indexByMap) === 0) {
                        yield end($result);
                    } else {
                        yield from $result;
                    }
                } elseif (is_object(current($result))) {
                    yield $result;
                } else {
                    yield array_merge(...$result);
                }
            }
        } finally {
            $this->cleanup();
        }
    }

    final protected function statement(): Result
    {
        if ($this->stmt === null) {
            throw new LogicException('Uninitialized _stmt property');
        }

        return $this->stmt;
    }

    final protected function resultSetMapping(): ResultSetMapping
    {
        if ($this->rsm === null) {
            throw new LogicException('Uninitialized _rsm property');
        }

        return $this->rsm;
    }

    /**
     * Hydrates all rows returned by the passed statement instance at once.
     *
     * @phpstan-param array<string, mixed> $hints
     */
    public function hydrateAll(Result $stmt, ResultSetMapping $resultSetMapping, array $hints = []): mixed
    {
        $this->stmt  = $stmt;
        $this->rsm   = $resultSetMapping;
        $this->hints = $hints;

        $this->em->getEventManager()->addEventListener([Events::onClear], $this);
        $this->prepare();

        try {
            $result = $this->hydrateAllData();
        } finally {
            $this->cleanup();
        }

        return $result;
    }

    /**
     * When executed in a hydrate() loop we have to clear internal state to
     * decrease memory consumption.
     */
    public function onClear(mixed $eventArgs): void
    {
    }

    /**
     * Executes one-time preparation tasks, once each time hydration is started
     * through {@link hydrateAll} or {@link toIterable()}.
     */
    protected function prepare(): void
    {
        $this->initializeResultColumnNames();
    }

    /**
     * Executes one-time cleanup tasks at the end of a hydration that was initiated
     * through {@link hydrateAll} or {@link toIterable()}.
     */
    protected function cleanup(): void
    {
        $this->statement()->free();

        $this->stmt              = null;
        $this->rsm               = null;
        $this->cache             = [];
        $this->metadataCache     = [];
        $this->mappedColumnNames = [];
        $this->resultColumnNames = [];

        $this
            ->em
            ->getEventManager()
            ->removeEventListener([Events::onClear], $this);
    }

    protected function cleanupAfterRowIteration(): void
    {
    }

    /** @param array<string, mixed> $row */
    protected function getResultColumnValue(array $row, string $mappedColumnName): mixed
    {
        return $row[$this->getResultColumnName($mappedColumnName)];
    }

    /** @param array<string, mixed> $row */
    protected function hasResultColumnValue(array $row, string $mappedColumnName): bool
    {
        return isset($row[$this->getResultColumnName($mappedColumnName)]);
    }

    protected function getResultColumnName(string $mappedColumnName): string
    {
        return $this->resultColumnNames[$mappedColumnName] ?? $mappedColumnName;
    }

    /**
     * Hydrates a single row from the current statement instance.
     *
     * Template method.
     *
     * @param mixed[] $row    The row data.
     * @param mixed[] $result The result to fill.
     *
     * @throws HydrationException
     */
    protected function hydrateRowData(array $row, array &$result): void
    {
        throw new HydrationException('hydrateRowData() not implemented by this hydrator.');
    }

    /**
     * Hydrates all rows from the current statement instance at once.
     */
    abstract protected function hydrateAllData(): mixed;

    /**
     * Processes a row of the result set.
     *
     * Used for identity-based hydration (HYDRATE_OBJECT and HYDRATE_ARRAY).
     * Puts the elements of a result row into a new array, grouped by the dql alias
     * they belong to. The column names in the result set are mapped to their
     * field names during this procedure as well as any necessary conversions on
     * the values applied. Scalar values are kept in a specific key 'scalars'.
     *
     * @param mixed[] $data SQL Result Row.
     * @phpstan-param array<string, string> $id                 Dql-Alias => ID-Hash.
     * @phpstan-param array<string, bool>   $nonemptyComponents Does this DQL-Alias has at least one non NULL value?
     *
     * @return array<string, array<string, mixed>> An array with all the fields
     *                                             (name => value) of the data
     *                                             row, grouped by their
     *                                             component alias.
     * @phpstan-return array{
     *                   data: array<array-key, array>,
     *                   newObjects?: array<array-key, array{
     *                       class: ReflectionClass,
     *                       args: array,
     *                       obj: object
     *                   }>,
     *                   scalars?: array
     *               }
     */
    protected function gatherRowData(array $data, array &$id, array &$nonemptyComponents): array
    {
        $rowData = ['data' => [], 'newObjects' => []];

        foreach ($this->rsm->newObjectMappings as $mapping) {
            if (! array_key_exists($mapping['objIndex'], $this->rsm->newObject)) {
                $this->rsm->newObject[$mapping['objIndex']] = $mapping['className'];
            }
        }

        foreach ($this->rsm->newObject as $objIndex => $newObject) {
            $rowData['newObjects'][$objIndex]['class'] = new ReflectionClass($newObject);
            $rowData['newObjects'][$objIndex]['args']  = [];
        }

        foreach ($data as $key => $value) {
            $cacheKeyInfo = $this->hydrateColumnInfo($key);
            if ($cacheKeyInfo === null) {
                continue;
            }

            $fieldName = $cacheKeyInfo['fieldName'];

            switch (true) {
                case isset($cacheKeyInfo['isNewObjectParameter']):
                    $argIndex = $cacheKeyInfo['argIndex'];
                    $objIndex = $cacheKeyInfo['objIndex'];
                    $type     = $cacheKeyInfo['type'];
                    $value    = $type->convertToPHPValue($value, $this->platform);

                    if ($value !== null && isset($cacheKeyInfo['enumType'])) {
                        $value = $this->buildEnum($value, $cacheKeyInfo['enumType']);
                    }

                    $rowData['newObjects'][$objIndex]['args'][$argIndex] = $value;
                    break;

                case isset($cacheKeyInfo['isScalar']):
                    $type  = $cacheKeyInfo['type'];
                    $value = $type->convertToPHPValue($value, $this->platform);

                    if ($value !== null && isset($cacheKeyInfo['enumType'])) {
                        $value = $this->buildEnum($value, $cacheKeyInfo['enumType']);
                    }

                    $rowData['scalars'][$fieldName] = $value;

                    break;

                //case (isset($cacheKeyInfo['isMetaColumn'])):
                default:
                    $dqlAlias = $cacheKeyInfo['dqlAlias'];
                    $type     = $cacheKeyInfo['type'];

                    // If there are field name collisions in the child class, then we need
                    // to only hydrate if we are looking at the correct discriminator value
                    $discriminatorColumn = $cacheKeyInfo['discriminatorColumn'] ?? null;

                    if (
                        $discriminatorColumn !== null
                        && $this->hasResultColumnValue($data, $discriminatorColumn)
                        && ! in_array((string) $this->getResultColumnValue($data, $discriminatorColumn), $cacheKeyInfo['discriminatorValues'], true)
                    ) {
                        break;
                    }

                    // in an inheritance hierarchy the same field could be defined several times.
                    // We overwrite this value so long we don't have a non-null value, that value we keep.
                    // Per definition it cannot be that a field is defined several times and has several values.
                    if (isset($rowData['data'][$dqlAlias][$fieldName])) {
                        break;
                    }

                    $rowData['data'][$dqlAlias][$fieldName] = $type
                        ? $type->convertToPHPValue($value, $this->platform)
                        : $value;

                    if ($rowData['data'][$dqlAlias][$fieldName] !== null && isset($cacheKeyInfo['enumType'])) {
                        $rowData['data'][$dqlAlias][$fieldName] = $this->buildEnum($rowData['data'][$dqlAlias][$fieldName], $cacheKeyInfo['enumType']);
                    }

                    if ($cacheKeyInfo['isIdentifier'] && $value !== null) {
                        $id[$dqlAlias]                .= '|' . $value;
                        $nonemptyComponents[$dqlAlias] = true;
                    }

                    break;
            }
        }

        $nestedEntities = [];
        /**@var string $argAlias */
        foreach ($this->resultSetMapping()->nestedNewObjectArguments as ['ownerIndex' => $ownerIndex, 'argIndex' => $argIndex, 'argAlias' => $argAlias]) {
            if (array_key_exists($argAlias, $rowData['newObjects'])) {
                ksort($rowData['newObjects'][$argAlias]['args']);
                $rowData['newObjects'][$ownerIndex]['args'][$argIndex] = $rowData['newObjects'][$argAlias]['class']->newInstanceArgs($rowData['newObjects'][$argAlias]['args']);
                unset($rowData['newObjects'][$argAlias]);
            } elseif (array_key_exists($argAlias, $rowData['data'])) {
                if (! array_key_exists($argAlias, $nestedEntities)) {
                    $nestedEntities[$argAlias]  = '';
                    $rowData['data'][$argAlias] = $this->hydrateNestedEntity($rowData['data'][$argAlias], $argAlias);
                }

                $rowData['newObjects'][$ownerIndex]['args'][$argIndex] = $rowData['data'][$argAlias];
            } else {
                throw new LogicException($argAlias . ' does not exist');
            }
        }

        foreach (array_keys($nestedEntities) as $entity) {
            unset($rowData['data'][$entity]);
        }

        foreach ($rowData['newObjects'] as $objIndex => $newObject) {
            ksort($rowData['newObjects'][$objIndex]['args']);
            $obj = $rowData['newObjects'][$objIndex]['class']->newInstanceArgs($rowData['newObjects'][$objIndex]['args']);

            $rowData['newObjects'][$objIndex]['obj'] = $obj;
        }

        return $rowData;
    }

    /** @param mixed[] $data pre-hydrated SQL Result Row. */
    protected function hydrateNestedEntity(array $data, string $dqlAlias): mixed
    {
        return $data;
    }

    /**
     * Processes a row of the result set.
     *
     * Used for HYDRATE_SCALAR. This is a variant of _gatherRowData() that
     * simply converts column names to field names and properly converts the
     * values according to their types. The resulting row has the same number
     * of elements as before.
     *
     * @param mixed[] $data
     * @phpstan-param array<string, mixed> $data
     *
     * @return mixed[] The processed row.
     * @phpstan-return array<string, mixed>
     */
    protected function gatherScalarRowData(array &$data): array
    {
        $rowData = [];

        foreach ($data as $key => $value) {
            $cacheKeyInfo = $this->hydrateColumnInfo($key);
            if ($cacheKeyInfo === null) {
                continue;
            }

            $fieldName = $cacheKeyInfo['fieldName'];

            // WARNING: BC break! We know this is the desired behavior to type convert values, but this
            // erroneous behavior exists since 2.0 and we're forced to keep compatibility.
            if (! isset($cacheKeyInfo['isScalar'])) {
                $type  = $cacheKeyInfo['type'];
                $value = $type ? $type->convertToPHPValue($value, $this->platform) : $value;

                $fieldName = $cacheKeyInfo['dqlAlias'] . '_' . $fieldName;
            }

            $rowData[$fieldName] = $value;
        }

        return $rowData;
    }

    /**
     * Retrieve column information from ResultSetMapping.
     *
     * @param string $key Column name
     *
     * @return mixed[]|null
     * @phpstan-return array<string, mixed>|null
     */
    protected function hydrateColumnInfo(string $key): array|null
    {
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $mappingKey = $this->mappedColumnNames[$key] ?? $key;

        switch (true) {
            // NOTE: Most of the times it's a field mapping, so keep it first!!!
            case isset($this->rsm->fieldMappings[$mappingKey]):
                $classMetadata = $this->getClassMetadata($this->rsm->declaringClasses[$mappingKey]);
                $fieldName     = $this->rsm->fieldMappings[$mappingKey];
                $fieldMapping  = $classMetadata->fieldMappings[$fieldName];
                $ownerMap      = $this->rsm->columnOwnerMap[$mappingKey];
                $columnInfo    = [
                    'isIdentifier' => in_array($fieldName, $classMetadata->identifier, true),
                    'fieldName'    => $fieldName,
                    'type'         => Type::getType($fieldMapping->type),
                    'dqlAlias'     => $ownerMap,
                    'enumType'     => $this->rsm->enumMappings[$mappingKey] ?? null,
                ];

                // the current discriminator value must be saved in order to disambiguate fields hydration,
                // should there be field name collisions
                if ($classMetadata->parentClasses && isset($this->rsm->discriminatorColumns[$ownerMap])) {
                    return $this->cache[$key] = array_merge(
                        $columnInfo,
                        [
                            'discriminatorColumn' => $this->rsm->discriminatorColumns[$ownerMap],
                            'discriminatorValue'  => $classMetadata->discriminatorValue,
                            'discriminatorValues' => $this->getDiscriminatorValues($classMetadata),
                        ],
                    );
                }

                return $this->cache[$key] = $columnInfo;

            case isset($this->rsm->newObjectMappings[$mappingKey]):
                // WARNING: A NEW object is also a scalar, so it must be declared before!
                $mapping = $this->rsm->newObjectMappings[$mappingKey];

                return $this->cache[$key] = [
                    'isScalar'             => true,
                    'isNewObjectParameter' => true,
                    'fieldName'            => $this->rsm->scalarMappings[$mappingKey],
                    'type'                 => Type::getType($this->rsm->typeMappings[$mappingKey]),
                    'argIndex'             => $mapping['argIndex'],
                    'objIndex'             => $mapping['objIndex'],
                    'enumType'             => $this->rsm->enumMappings[$mappingKey] ?? null,
                ];

            case isset($this->rsm->scalarMappings[$mappingKey], $this->hints[LimitSubqueryWalker::FORCE_DBAL_TYPE_CONVERSION]):
                return $this->cache[$key] = [
                    'fieldName' => $this->rsm->scalarMappings[$mappingKey],
                    'type'      => Type::getType($this->rsm->typeMappings[$mappingKey]),
                    'dqlAlias'  => '',
                    'enumType'  => $this->rsm->enumMappings[$mappingKey] ?? null,
                ];

            case isset($this->rsm->scalarMappings[$mappingKey]):
                return $this->cache[$key] = [
                    'isScalar'  => true,
                    'fieldName' => $this->rsm->scalarMappings[$mappingKey],
                    'type'      => Type::getType($this->rsm->typeMappings[$mappingKey]),
                    'enumType'  => $this->rsm->enumMappings[$mappingKey] ?? null,
                ];

            case isset($this->rsm->metaMappings[$mappingKey]):
                // Meta column (has meaning in relational schema only, i.e. foreign keys or discriminator columns).
                $fieldName = $this->rsm->metaMappings[$mappingKey];
                $dqlAlias  = $this->rsm->columnOwnerMap[$mappingKey];
                $type      = isset($this->rsm->typeMappings[$mappingKey])
                    ? Type::getType($this->rsm->typeMappings[$mappingKey])
                    : null;

                // Cache metadata fetch
                $this->getClassMetadata($this->rsm->aliasMap[$dqlAlias]);

                return $this->cache[$key] = [
                    'isIdentifier' => isset($this->rsm->isIdentifierColumn[$dqlAlias][$mappingKey]),
                    'isMetaColumn' => true,
                    'fieldName'    => $fieldName,
                    'type'         => $type,
                    'dqlAlias'     => $dqlAlias,
                    'enumType'     => $this->rsm->enumMappings[$mappingKey] ?? null,
                ];
        }

        // this column is a left over, maybe from a LIMIT query hack for example in Oracle or DB2
        // maybe from an additional column that has not been defined in a NativeQuery ResultSetMapping.
        return null;
    }

    private function initializeResultColumnNames(): void
    {
        if (! isset($this->hints[Query::HINT_INTERNAL_GENERATED_RESULT_SET_MAPPING])) {
            return;
        }

        $mappedColumnNames = $this->getMappedColumnNames();
        if ($mappedColumnNames === []) {
            return;
        }

        try {
            $columnCount = $this->statement()->columnCount();
        } catch (Throwable) {
            return;
        }

        for ($index = 0; $index < $columnCount; ++$index) {
            try {
                $resultColumnName = $this->statement()->getColumnName($index);
            } catch (Throwable) {
                $this->mappedColumnNames = [];
                $this->resultColumnNames = [];

                return;
            }

            $mappedColumnName = $this->resolveMappedColumnName($resultColumnName, $mappedColumnNames);
            if ($mappedColumnName === null) {
                continue;
            }

            $this->mappedColumnNames[$resultColumnName] = $mappedColumnName;
            $this->resultColumnNames[$mappedColumnName] = $resultColumnName;
        }
    }

    /** @return array<string, string|null> */
    private function getMappedColumnNames(): array
    {
        $mappedColumnNames = [];

        $resultSetMappingColumnNames = [
            array_keys($this->resultSetMapping()->fieldMappings),
            array_keys($this->resultSetMapping()->scalarMappings),
            array_keys($this->resultSetMapping()->metaMappings),
            array_keys($this->resultSetMapping()->newObjectMappings),
            $this->resultSetMapping()->discriminatorColumns,
            $this->resultSetMapping()->indexByMap,
        ];

        foreach ($resultSetMappingColumnNames as $columnNames) {
            foreach ($columnNames as $columnName) {
                if ($columnName === '') {
                    continue;
                }

                $foldedColumnName = strtolower($columnName);

                if (! array_key_exists($foldedColumnName, $mappedColumnNames)) {
                    $mappedColumnNames[$foldedColumnName] = $columnName;

                    continue;
                }

                if ($mappedColumnNames[$foldedColumnName] !== $columnName) {
                    $mappedColumnNames[$foldedColumnName] = null;
                }
            }
        }

        return $mappedColumnNames;
    }

    /** @param array<string, string|null> $mappedColumnNames */
    private function resolveMappedColumnName(string $resultColumnName, array $mappedColumnNames): string|null
    {
        if (
            isset($this->resultSetMapping()->fieldMappings[$resultColumnName])
            || isset($this->resultSetMapping()->scalarMappings[$resultColumnName])
            || isset($this->resultSetMapping()->metaMappings[$resultColumnName])
            || isset($this->resultSetMapping()->newObjectMappings[$resultColumnName])
            || in_array($resultColumnName, $this->resultSetMapping()->discriminatorColumns, true)
            || in_array($resultColumnName, $this->resultSetMapping()->indexByMap, true)
        ) {
            return $resultColumnName;
        }

        return $mappedColumnNames[strtolower($resultColumnName)] ?? null;
    }

    /**
     * @return string[]
     * @phpstan-return non-empty-list<string>
     */
    private function getDiscriminatorValues(ClassMetadata $classMetadata): array
    {
        $values = array_map(
            fn (string $subClass): string => (string) $this->getClassMetadata($subClass)->discriminatorValue,
            $classMetadata->subClasses,
        );

        $values[] = (string) $classMetadata->discriminatorValue;

        return $values;
    }

    /**
     * Retrieve ClassMetadata associated to entity class name.
     */
    protected function getClassMetadata(string $className): ClassMetadata
    {
        if (! isset($this->metadataCache[$className])) {
            $this->metadataCache[$className] = $this->em->getClassMetadata($className);
        }

        return $this->metadataCache[$className];
    }

    /**
     * Register entity as managed in UnitOfWork.
     *
     * @param mixed[] $data
     *
     * @todo The "$id" generation is the same of UnitOfWork#createEntity. Remove this duplication somehow
     */
    protected function registerManaged(ClassMetadata $class, object $entity, array $data): void
    {
        if ($class->isIdentifierComposite) {
            $id = [];

            foreach ($class->identifier as $fieldName) {
                $id[$fieldName] = isset($class->associationMappings[$fieldName]) && $class->associationMappings[$fieldName]->isToOneOwningSide()
                    ? $data[$class->associationMappings[$fieldName]->joinColumns[0]->name]
                    : $data[$fieldName];
            }
        } else {
            $fieldName = $class->identifier[0];
            $id        = [
                $fieldName => isset($class->associationMappings[$fieldName]) && $class->associationMappings[$fieldName]->isToOneOwningSide()
                    ? $data[$class->associationMappings[$fieldName]->joinColumns[0]->name]
                    : $data[$fieldName],
            ];
        }

        $this->em->getUnitOfWork()->registerManaged($entity, $id, $data);
    }

    /**
     * @param class-string<BackedEnum> $enumType
     *
     * @return BackedEnum|array<BackedEnum>
     */
    final protected function buildEnum(mixed $value, string $enumType): BackedEnum|array
    {
        $reflection  = new ReflectionEnum($enumType);
        $isIntBacked = $reflection->isBacked() && $reflection->getBackingType()->getName() === 'int';

        if (is_array($value)) {
            return array_map(
                static fn ($value) => $enumType::from($isIntBacked ? (int) $value : $value),
                $value,
            );
        }

        $value = $isIntBacked ? (int) $value : $value;

        return $enumType::from($value);
    }
}
