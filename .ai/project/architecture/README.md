# Doctrine ORM Architecture Knowledge

This directory is the accepted Alatyr architecture-knowledge layer for the
atvardovsky Doctrine ORM fork.

Status: enabled
Path: `.ai/project/architecture/README.md`
Last reviewed: 2026-08-26
Owner: `@atvardovsky`

## Canonical Sources

- `docs/en/reference/architecture.rst`
- `composer.json`
- `src/`
- `docs/en/reference/basic-mapping.rst`
- `docs/en/reference/unitofwork.rst`
- `docs/en/reference/advanced-configuration.rst`
- `docs/en/reference/second-level-cache.rst`
- `docs/en/tutorials/pagination.rst`
- `.ai/project/blueprint.md`
- `.ai/project/business-logic.md`
- `.ai/project/source-of-truth-registry.md`

## Accepted Areas

- Mapping metadata: source under `src/Mapping/`, documented by mapping
  reference pages and tested under `tests/Tests/ORM/`.
- UnitOfWork and persistence lifecycle: `src/UnitOfWork.php`, persisters,
  hydrators, and functional tests.
- Query and hydration: DQL/query parser, SQL walkers, hydrators, and query
  behavior documentation.
- Offset and cursor pagination: `src/Tools/Pagination/`, the pagination
  tutorial, and focused pagination tests.
- DBAL integration: dependency boundary through Doctrine DBAL and platform
  matrix validation.
- Cache and proxy behavior: second-level cache, proxy, and lazy-loading
  surfaces where present in source and docs.
- Tooling and validation: Composer, PHPUnit, PHPStan, PHPCS, and GitHub
  Actions workflows.

## Use

Architecture assistance must route through this catalog and then verify the
specific canonical Doctrine docs, source, and tests before claiming accepted
architecture facts. This layer is an index and discussion aid; it does not
replace upstream Doctrine architecture documentation or code review.

## Status Meanings

Accepted architecture records are usable for routing and implementation. Proposed records require owner review before they guide source changes. Deprecated or blocked records may be cited only as historical evidence.

## Architecture Patterns And Items

Pattern IDs, area IDs, and evidence paths in `catalog.json` are the machine-readable index. Source code, tests, public docs, and the source-of-truth registry remain the canonical owners of Doctrine ORM behavior.

Evidence revision: 68a7b23a93b0849adb6f42b31410a04dc9290a56
