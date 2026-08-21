# Doctrine ORM Blueprint Index

This file is the target-owned Alatyr blueprint index for the atvardovsky
Doctrine ORM fork. It routes accepted project facts to their canonical owners;
it does not replace Doctrine public docs, source code, tests, or CI files.

Status: accepted for this Alatyr adapter
Path: `.ai/project/blueprint.md`
Last reviewed: 2026-08-21
Owner: `@atvardovsky`

## Rules

- Record only facts backed by Doctrine ORM repository evidence.
- Treat canonical Doctrine docs, source, tests, and validation files as
  stronger than this index when they conflict.
- Mark unknown or missing facts explicitly instead of filling gaps by guess.
- Require explicit approval before changing accepted public behavior,
  architecture boundaries, security posture, dependencies, CI gates, or
  destructive/live-service behavior.
- Keep this index synchronized when a change alters source-of-truth ownership,
  validation routing, or blueprint workflow behavior.
- Follow `.ai/project/commit-policy.md` before creating commits: each commit
  must have one logical scope and a detailed commit message written in English.

## Canonical Sources

Product purpose: `README.md`

Public behavior: `docs/en/reference/*.rst`, with implementation evidence in
`src/` and regression evidence in `tests/`.

Business logic routing: `.ai/project/business-logic.md` maps accepted ORM
behavior-rule families to their canonical Doctrine docs, source, and tests.

Architecture boundaries: `docs/en/reference/architecture.rst` and
`composer.json`.

Mapping and persistence model: `docs/en/reference/basic-mapping.rst`,
`docs/en/reference/unitofwork.rst`, `src/Mapping/`, `src/UnitOfWork.php`, and
related tests under `tests/Tests/ORM/`.

Security posture: `SECURITY.md` and `docs/en/reference/security.rst`.

Contribution and validation expectations: `CONTRIBUTING.md`,
`tests/README.markdown`, `phpunit.xml.dist`, `phpstan*.neon`,
`phpcs.xml.dist`, `composer.json`, and `.github/workflows/`.

Assistant source-of-truth routing: `.ai/project/source-of-truth-registry.md`,
`.ai/assistant/context-router.json`, `.ai/assistant/module-profile.md`, and
`.ai/assistant/operation-catalog.json`.

Commit policy: `.ai/project/commit-policy.md`.

## Blueprint Operations

`create-project-blueprint` is enabled for creating, repairing, or rechecking
this index and equivalent source-of-truth docs from target evidence.

`product-change` is enabled for accepted behavior, architecture, data,
runtime, or public-contract changes. It must identify changed facts, canonical
owners, affected docs/source/tests, approval needs, and validation.

The optional consistency-map, diagram, test-first, team-collaboration,
architecture-catalog, and AI-infrastructure modules are not enabled in this
installation. Blueprint work therefore uses manual impact closure through the
registry, target docs/source/tests, and validation output.

## Local Validation Notes

This workspace has PHP 8.2 at `/usr/local/bin/php8` and Composer 2.8 at
`/usr/local/bin/composer8`. The plain `php` and `composer` commands are older
and are not suitable for this branch.

Local SQLite is available but lacks the SQL `SQRT()` function, so the full PHPUnit suite runs to completion with a 1G PHP memory limit but reports SQLite `SQRT()` errors in `Doctrine\Tests\ORM\Functional\QueryDqlFunctionTest::testFunctionSqrt` and `Doctrine\Tests\ORM\Functional\Ticket\GH7941Test::typesShouldBeConvertedForDQLFunctions` in this
environment. Treat that as an environment constraint unless the SQLite runtime
is changed or the test profile is adjusted.

## Full Alatyr Capability Routing

Full Alatyr work routes through the enabled module graph in `.ai/assistant/module-profile.md`. Optional capability records are accepted target-owned routing layers and must stay synchronized with `.ai/project/source-of-truth-registry.md`, `.ai/project/consistency-map.json`, `.ai/assistant/context-router.json`, `.ai/assistant/operation-catalog.json`, `.ai/assistant/gates/index.json`, and the generated bootstrap index.
