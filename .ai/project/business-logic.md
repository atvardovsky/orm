# Doctrine ORM Business Logic Layer

This is the target-owned Alatyr business-logic layer for the atvardovsky
Doctrine ORM fork.

Path: `.ai/project/business-logic.md`
Status: accepted for this Alatyr adapter
Last reviewed: 2026-08-21
Owner: `@atvardovsky`

## Purpose

For this library, "business logic" means accepted Doctrine ORM behavior
contracts: how object mapping, persistence, UnitOfWork, querying, repositories,
transactions, caching, and security assumptions are documented, implemented,
and tested.

This file is an Alatyr routing layer. It does not create new Doctrine behavior
and does not replace public docs, source code, tests, or CI.

## Canonical Rule Families

### Product And Public Behavior

Canonical owners:

- `README.md`
- `docs/en/reference/*.rst`
- selected implementation under `src/`
- selected regression coverage under `tests/`

Use for accepted public behavior, public contract wording, examples, and
developer-facing ORM expectations.

### Mapping And Metadata

Canonical owners:

- `docs/en/reference/basic-mapping.rst`
- `docs/en/reference/association-mapping.rst`
- `docs/en/reference/inheritance-mapping.rst`
- `docs/en/reference/metadata-drivers.rst`
- `src/Mapping/`
- related mapping tests under `tests/Tests/ORM/`

Use for entity metadata, associations, inheritance, typed fields, mapping
drivers, and metadata validation behavior.

### Persistence Lifecycle And UnitOfWork

Canonical owners:

- `docs/en/reference/working-with-objects.rst`
- `docs/en/reference/unitofwork.rst`
- `docs/en/reference/unitofwork-associations.rst`
- `docs/en/reference/change-tracking-policies.rst`
- `src/UnitOfWork.php`
- selected persister and functional tests under `tests/Tests/ORM/`

Use for identity, lifecycle state, change tracking, cascade behavior, flush
semantics, and association persistence rules.

### Query And Hydration Behavior

Canonical owners:

- `docs/en/reference/dql-doctrine-query-language.rst`
- `docs/en/reference/query-builder.rst`
- `docs/en/reference/native-sql.rst`
- `docs/en/reference/partial-objects.rst`
- `docs/en/reference/partial-hydration.rst`
- `src/Query/`
- query, parser, hydrator, and functional tests under `tests/Tests/ORM/`

Use for DQL grammar and functions, QueryBuilder behavior, native SQL mapping,
hydration, partial object constraints, and query result semantics.

### Transactions, Concurrency, And Caching

Canonical owners:

- `docs/en/reference/transactions-and-concurrency.rst`
- `docs/en/reference/caching.rst`
- `docs/en/reference/second-level-cache.rst`
- relevant source under `src/Cache/`, persisters, and transaction paths
- related functional tests under `tests/Tests/ORM/`

Use for transaction boundaries, lock behavior, first/second-level cache
semantics, and concurrency-sensitive behavior.

### Security-Relevant ORM Rules

Canonical owners:

- `SECURITY.md`
- `docs/en/reference/security.rst`
- query construction code and tests when security-relevant behavior changes

Use for SQL-injection assumptions, safe parameter use, mass-assignment
warnings, and private vulnerability reporting.

## Alatyr Routing

Business-change work must load:

- `.ai/project/business-logic.md`
- `.ai/project/blueprint.md`
- `.ai/project/source-of-truth-registry.md`
- `.ai/assistant/context/profiles/business-change.json`
- selected canonical Doctrine docs, source, tests, and validation files

If a request changes accepted behavior, route through `product-change` and
`.ai/assistant/flows/blueprint-driven-change.flow.md`.

If a request only explains or reviews behavior, keep it read-only and cite the
canonical owners inspected.

If a request changes implementation without accepting a new behavior contract,
route through `code-local` but still check this layer when behavior drift is
possible.

## Sync Rules

- Update this layer when a new business rule family or canonical owner is
  accepted.
- Update `.ai/project/blueprint.md` and
  `.ai/project/source-of-truth-registry.md` when this layer's ownership routing
  changes.
- Update `.ai/assistant/context/profiles/business-change.json` when the
  minimum context for accepted business changes changes.
- Update docs, source, and tests together for accepted behavior changes.
- Report missing or conflicting owners instead of inventing behavior.

## Validation

Use target-recorded validation:

- `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpunit`
- `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=1G`
- `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpstan analyse -c phpstan-dbal3.neon --memory-limit=1G`
- `/usr/local/bin/php8 -d memory_limit=1G vendor/bin/phpcs -d memory_limit=1G`
- relevant docs/manual review

Known local blocker: SQLite 3.31.1 lacks SQL `SQRT()`, so the full PHPUnit suite reports errors in `Doctrine\Tests\ORM\Functional\QueryDqlFunctionTest::testFunctionSqrt` and `Doctrine\Tests\ORM\Functional\Ticket\GH7941Test::typesShouldBeConvertedForDQLFunctions` in this environment unless SQLite/runtime/test profile changes.
